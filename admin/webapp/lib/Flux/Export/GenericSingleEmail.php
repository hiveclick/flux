<?php
namespace Flux\Export;

use \Flux\Export\ExportAbstract;
use Mojavi\Util\StringTools;

/**
 * Processes leads by sending them via email in an attachment
 * @author Mark Hobson
 */
class GenericSingleEmail extends ExportAbstract {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setClientExportType(parent::CLIENT_EXPORT_TYPE_EMAIL);
		$this->setName('Generic Single Email Export');
		$this->setDescription('Send an email to the recipients with multiple leads sent in multiple emails');
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array $leads
	 * @return boolean
	 */
	function send($export_queue_items) {
		/*
		 * Here we will handle realtime emails, where each lead is sent out in an individual email
		*/
		$start_time = microtime(true);
		StringTools::consoleWrite('  Sending as single emails', 'Sending', StringTools::CONSOLE_COLOR_RED);
		foreach ($export_queue_items as $key => $export_queue_item) {
			StringTools::consoleWrite('  Sending as single emails', 'Sending ' . number_format($key, 0, null, ',') . ' (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
			$buffer = array();
		
			$buffer[] = 'Please review the following lead:';
			$buffer[] = '';
		
			$qs = $export_queue_item->getQs();
			foreach ($this->getExport()->getClientExport()->getMapping() as $mapping) {
				$data_field = \Flux\DataField::retrieveDataFieldFromId($mapping['datafield_id']);
		
				$line = '<b>' . $data_field->getDescription() . '</b>: ';
				if (isset($qs[$data_field->getKeyName()])) {
					$line .= '&nbsp;&nbsp;&nbsp;' . $qs[$data_field->getKeyName()] . '';
				}
				$buffer[] = $line;
			}
			$buffer[] = '';
			$buffer[] = '';
			$buffer[] = '';
		
			// Format the email and send it out
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
		
			$text = new \Zend\Mime\Part(implode("\n", $buffer));
			$text->type = "text/plain";
		
			$html = new \Zend\Mime\Part(implode("<br />", $buffer));
			$html->type = "text/html";
		
			$body = new \Zend\Mime\Message();
			$body->setParts(array($html));
		
			$message = new \Zend\Mail\Message();
			$message->setBody($body);
			$message->setFrom(MO_MAIL_USERNAME, 'Leads');
			$message->getHeaders()->addHeaderLine('Content-Type', 'multipart/alternative');
		
			foreach ($this->getExport()->getClientExport()->getEmailAddress() as $email_address) {
				$message->addTo($email_address);
			}
			$message->setSubject('Leads for ' . $this->getExport()->getName());
		
			try {
				$transport->send($message);
				$export_queue_item->setResponse('Sent');
				$export_queue_item->setIsError(false);
				$export_queue_item->update();
			} catch (\Exception $e) {
				$export_queue_item->setResponse($e->getMessage());
				$export_queue_item->setIsError(true);
				$export_queue_item->update();
			}
		
			// Update the percentage done
			$this->getExport()->setPercentComplete((($key / count($leads)) * 40) + 50);
			$this->getExport()->update();
		}
		$this->getExport()->setSendingRecordsTime((microtime(true) - $start_time));
		StringTools::consoleWrite('  Sending as single emails', 'Sent (' . number_format((microtime(true) - $start_time), 3) . 's)', StringTools::CONSOLE_COLOR_GREEN, true);
	}
	
}