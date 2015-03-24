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
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_EMAIL);
		$this->setName('Generic Single Email Export');
		$this->setDescription('Send an email to the recipients with multiple leads sent in multiple emails');
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array $split_queue_attempts
	 * @return boolean
	 */
	function send($split_queue_attempts, $is_test = false) {
		/*
		 * Here we will handle realtime emails, where each lead is sent out in an individual email
		*/

		/* @var $split_queue_attempt \Flux\SplitQueueAttempt */
		foreach ($split_queue_attempts as $key => $split_queue_attempt) {
		    $split_queue_attempt->setStartTime(microtime(true));
		    		    
			$buffer = array();
			$buffer[] = 'Please review the following lead:';
			$buffer[] = '';
		
			$params = $split_queue_attempt->mergeLead();
			/* @var $mapping \Flux\FulfillmentMap */
			foreach ($params as $key => $value) {
		        $line = '<b>' . $key . '</b>: ';
			    $line .= ' ' . $value;
			    $buffer[] = $line;
			}
			
			$buffer[] = '';
			$buffer[] = '';
			$buffer[] = '';
		
			$split_queue_attempt->setRequest(implode("\n", $buffer));
			
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
		
			foreach ($split_queue_attempt->getFulfillment()->getFulfillment()->getEmailAddress() as $email_address) {
				$message->addTo($email_address);
			}
			$message->setSubject('Leads for ' . date('m/d/Y'));
		
			if (!$is_test) {
    			try {
    				$transport->send($message);
                    $split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
    				$split_queue_attempt->setResponse('Sent');
    				$split_queue_attempt->setIsError(false);
    			} catch (\Exception $e) {
    			    $split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
    				$split_queue_attempt->setResponse($e->getMessage());
    				$split_queue_attempt->setErrorMessage($e->getMessage());
    				$split_queue_attempt->setIsError(false);
    			}
			} else {
			    $split_queue_attempt->setResponseTime(microtime(true) - $split_queue_attempt->getStartTime());
			    $split_queue_attempt->setResponse('Sent');
			    $split_queue_attempt->setIsError(false);
			}
		}
		return $split_queue_attempts;
	}
	
}