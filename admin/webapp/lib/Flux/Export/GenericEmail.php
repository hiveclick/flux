<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;
use Mojavi\Util\StringTools;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericEmail extends ExportAbstract {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_EMAIL);
		$this->setName('Generic Email Export');
		$this->setDescription('Send an email to the recipients with multiple leads in an attachment');
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $leads
	 * @return boolean
	 */
	function send($export_queue_items) {
		$start_time = microtime(true);
		StringTools::consoleWrite('  Building file of recipients', 'Building', StringTools::CONSOLE_COLOR_RED);
		// Create an export file that we will for the FTP or email attachment
		if (($fh = fopen($this->getExportFolder() . '/original.txt', 'w')) !== false) {
			$mapping = $this->getExport()->getFulfillment()->getMapping();
		
			// Write out our headers
			$header = array();
			foreach ($mapping as $mapping_item) {
				$data_field = \Flux\DataField::retrieveDataFieldFromId($mapping_item['datafield_id']);
				$header[] = $data_field->getName();
			}
			fwrite($fh, implode("\t", $header) . "\n");
		
			foreach ($export_queue_items as $key => $export_queue_item) {
				$line = array();
				$qs = $export_queue_item->getQs();
				foreach ($mapping as $mapping_item) {
					$data_field = \Flux\DataField::retrieveDataFieldFromId($mapping_item['datafield_id']);
					if (isset($qs[$data_field->getKeyName()])) {
						$line[] = trim($qs[$data_field->getKeyName()]);
					} else {
						$line[] = '';
					}
				}
				fwrite($fh, implode("\t", $line) . "\n");
		
				// Update the percentage done
				$this->getExport()->setPercentComplete((($key / count($queue_items)) * 40) + 50);
				$this->getExport()->update();
			}
		
			fclose($fh);
			StringTools::consoleWrite('  Building file of recipients', 'Built ' . number_format(count($queue_items), 0, null, ',') . ' (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
		} else {
			throw new \Exception('Cannot open export file ' . $this->getExportFolder() . '/original.txt');
		}
		$this->getExport()->setSendingRecordsTime((microtime(true) - $start_time));
		$this->getExport()->setPercentComplete(95);
		$this->getExport()->update();
		
		$start_time = microtime(true);
		StringTools::consoleWrite('  Sending as email w/ attachment', 'Sending', StringTools::CONSOLE_COLOR_RED);
		/*
		 * Here we will handle batch emails, where leads are sent as an email attachment
		*/
		$options = array(
				'name'	  => defined('MO_MAIL_HOSTNAME') ? MO_MAIL_HOSTNAME : '127.0.0.1',
				'host'	  => defined('MO_MAIL_HOSTNAME') ? MO_MAIL_HOSTNAME : '127.0.0.1'
		);
		if (defined('MO_MAIL_USERNAME') && trim(MO_MAIL_USERNAME) != '') {
			$options['connection_class'] = 'login';
			$options['connection_config']['username'] = defined('MO_MAIL_USERNAME') ? MO_MAIL_USERNAME : '';
			$options['connection_config']['password'] = defined('MO_MAIL_PASSWORD') ? MO_MAIL_PASSWORD : '';
		}
		$smtp_options = new \Zend\Mail\Transport\SmtpOptions($options);
		$transport = new \Zend\Mail\Transport\Smtp($smtp_options);
		
		$text = new \Zend\Mime\Part('Attached are your leads for ' . $this->getExport()->getName());
		$text->type = "text/plain";
		
		$html = new \Zend\Mime\Part('Attached are your leads for <b>' . $this->getExport()->getName() . '</b><p /><p />');
		$html->type = "text/html";
		
		$attachment = new \Zend\Mime\Part(fopen($this->getExportFolder() . '/original.txt', 'r'));
		$attachment->type = "file/attachment";
		$attachment->filename = str_replace(" ", "_", $this->getExport()->getName()) . '.txt';
		$attachment->disposition = 'text/plain';
		
		$body = new \Zend\Mime\Message();
		$body->setParts(array($html, $attachment));
		
		$message = new \Zend\Mail\Message();
		$message->setBody($body);
		$message->setFrom(MO_MAIL_USERNAME, 'Leads');
		
		foreach ($this->getExport()->getFulfillment()->getEmailAddress() as $email_address) {
			StringTools::consoleWrite('  Sending as email w/ attachment', $email_address . ' (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
			$message->addTo($email_address);
		}
		$message->setSubject('Leads for ' . $this->getExport()->getName());
		
		try {
			$transport->send($message);
			$export_queue = new ExportQueue($this->getExportId());
			$export_queue->updateMultiple(array('export_id' => $this->getExportId()), array('$set' => array('response' => 'Sent', 'is_error' => false)));
		} catch (\Exception $e) {
			$export_queue = new ExportQueue($this->getExportId());
			$export_queue->updateMultiple(array('export_id' => $this->getExportId()), array('$set' => array('response' => $e->getMessage(), 'is_error' => true)));
		}
		
		$transport->send($message);
		
		StringTools::consoleWrite('  Sent', $message->getSubject() . ' (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
	}
	
}