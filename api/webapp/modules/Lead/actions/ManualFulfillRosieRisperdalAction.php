<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ManualFulfillRosieRisperdalAction extends BasicRestAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		return parent::execute();
	}

	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function getInputForm() {
		return new \Flux\Lead();
	}
	
	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function executePost($input_form) {
		$ajax_form = new \Mojavi\Form\AjaxForm();
		
		$lead = new \Flux\Lead();
		$lead->setId($input_form->getId());
		$lead->query();
		if (!is_null($lead->getId())) {
			$ajax_form->setRecord($lead);
			
			// Send this lead as an email to Rosie at HMLM and Randi
			$buffer = array();
			
			$buffer[] = 'Please review Risperdal lead information below:';
			$buffer[] = '';
			$buffer[] = '<b>Id</b>: ' . $lead->getId();
			$buffer[] = '<b>Campaign #</b>: ' . 312;
			$buffer[] = '<b>Created</b>: ' . date('m/d/Y g:i a', $lead->getValue(\Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME, new \MongoDate())->sec);
			$buffer[] = '';
			
			$data = $lead->getD();
	
			$buffer[] = '<b>Name</b>: ' . $lead->getValue('first_name') . ' ' . $lead->getValue('last_name');
			$buffer[] = '<b>Email</b>: ' . $lead->getValue('email');
			
			$phone = $lead->getValue('phone1') . 
					 $lead->getValue('phone2') .
					 $lead->getValue('phone3');
			$buffer[] = '<b>Phone</b>: ' . $phone;
			$buffer[] = '<b>Address</b>: ' . $lead->getValue('address');
			$buffer[] = '<b>City</b>: ' . $lead->getValue('city');
			$buffer[] = '<b>State</b>: ' . $lead->getValue('state');
			$buffer[] = '<b>Zip</b>: ' . $lead->getValue('zip');
			$buffer[] = '';
			$datafield = new \Flux\DataField();
			$datafield->setTags('mesh');
			$datafield->setIgnorePagination(true);
			$datafields = $datafield->queryAll();
			foreach ($datafields as $datafield) {
				$field = '<b>' . $datafield->getDescription() . '</b>: ';
				$value = $lead->getValue($datafield->getKeyName(), null); 
				if ($datafield->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE && $value != null) {
					$field .= date('m/d/Y', $value->sec);   
				} else if ($datafield->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_DATETIME && $value != null) {
				   $field .= date('m/d/Y g:i:s a', $value->sec);
				} else if ($datafield->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY) {
					if (is_array($value)) {
						$field .= implode(', ', $value);
					} else if (is_string($value)) {
						$field .= $value;
					}
				} else if (is_array($value)) {
					$field .= implode(', ', $value);
				} else if (is_null($value)) {
					$field .= '-- missing --';
				} else {
					$field .= $value;
				}
				$buffer[] = $field;
			}
	
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
			
			$message->addTo('mark@doublesplash.com');
			$message->addTo('jon@doublesplash.com');
			$message->addTo('rdistin@hml-lawyers.com');
			$message->addTo('svgmtleads@thesandersfirm.com');
			
			$message->setSubject('Leads for ' . $lead->getOffer()->getName());
			
			try {
				$transport->send($message);
				$lead->addNote('Lead successfully sent to Rosie/Randi as Risperdal');
				$lead->setValue('fulfilled', 1);
				$lead->update();
			} catch (\Exception $e) {
				$this->getErrors()->addError('error', $e->getMessage());
				$lead->addNote('Lead unsuccessfully sent to Rosie/Randi as Risperdal: ' . $e->getMessage());
				$lead->update();
			}
		}
		return $ajax_form;
	}
}

?>