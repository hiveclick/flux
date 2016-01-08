<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class DataFieldValidateAction extends BasicRestAction
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
		return new \Flux\DataField();
	}
	
	/**
	 * Executes a POST request
	 * @param $input_form \Flux\DataField
	 */
	function executePost($input_form) {
		/* @var $input_form \Flux\DataField */
		$ajax_form = new BasicAjaxForm();
		
		
		/* @var $lead \Flux\Lead */
		$lead = new \Flux\Lead();
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Calling function for value " . $input_form->getName());
		try {
			$value = "";
			if ($input_form->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY) {
				$value = array();
			}
			
			$input_form->validateMappingFunc($value, $lead);

		} catch (\Exception $e) {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Exception: " . $e->getMessage());
			$input_form->setValidationResult($e->getMessage());
		}
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Validated value for " . $input_form->getName());
		$ajax_form->setRecord($input_form);
		
		return $ajax_form;
	}
}

?>