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
class FulfillmentMapValidateAction extends BasicRestAction
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
		return new \Flux\FulfillmentMap();
	}
	
	/**
	 * Executes a POST request
	 * @param $input_form \Flux\ClientExportMap
	 */
	function executePost($input_form) {
		$ajax_form = new BasicAjaxForm();
		
		
		/* @var $export_form \Flux\FulfillmentMap */
		$lead = new \Flux\Lead();
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Calling function for value " . $input_form->getDataField()->getDataFieldName());
		try {
			ob_start();
			$input_form->callMappingFunc("", $lead);
			$errors = ob_get_clean();
			if ($errors != '') {
				throw new \Exception($errors);
			}
		} catch (\Exception $e) {
			$this->getErrors()->addError("error", $e->getMessage());
		}
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Validated value for " . $input_form->getDataField()->getDataFieldName());
		$ajax_form->setRecord($input_form);
		
		return $ajax_form;
	}
}

?>