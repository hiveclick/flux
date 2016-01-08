<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Flux\Lead;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class LeadDataFieldAction extends BasicRestAction
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
	 * Handle a POST the same as a GET
	 */
	function executePost($input_form) {
		$ret_val = new BasicAjaxForm();
		$lead = new Lead();
		$lead->setId($input_form->getId());
		if ($lead->query() !== false) {
		
			// Convert the data field names and values to a key/value array
			$params = array();
			if ($this->getContext()->getRequest()->hasParameter('posting_url_data_field_name') && $this->getContext()->getRequest()->hasParameter('posting_url_data_field_value')) {
				$values = $this->getContext()->getRequest()->getParameter('posting_url_data_field_value', array());
				foreach ($this->getContext()->getRequest()->getParameter('posting_url_data_field_name', array()) as $key => $name) {
					if (isset($values[$key])) {
						$params[$name] = $values[$key];
					} else {
						$params[$name] = '';
					}
				}
			}
		
			// Save the parameters to the lead
			foreach ($params as $key => $value) {
				$lead->setValue($key, $value);
			}
			$lead->update();
			/*
			$update_array = array();
			if (in_array('_t', $lead->getModifiedColumns())) {
				foreach ($lead->getD() as $key => $value) {
					$update_array['$set']['_d.' . $key] = $value;
				}
			}
			if (in_array('_t', $lead->getModifiedColumns())) {
			   $update_array['$set']['_t'] = $lead->getT()->toArray();
			}
			if (in_array('_e', $lead->getModifiedColumns())) {
				$events = array();
				foreach ($lead->getE() as $event) {
					$events[] = $event->toArray();
				}
				$update_array['$set']['_e'] = $events;
			}
			
			$lead->update(array(), $update_array);
			*/
		}
		$ret_val->setRecord($lead);
		return $ret_val;
	}
}

?>