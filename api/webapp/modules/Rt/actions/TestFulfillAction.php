<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class TestFulfillAction extends BasicRestAction
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
	 * @return \Flux\Lead
	 */
	function getInputForm() {
		return new \Flux\Lead();
	}
	
	/**
	 * Executes a GET request
	 * @var $input_form \Flux\Lead
	 */
	function executeGet($input_form) {
		/* @var $ajax_form AjaxForm */
		$ajax_form = new \Mojavi\Form\AjaxForm();
		//$this->getErrors()->addError('error', 'Error fulfilling lead, bad email');
		// First try to find the lead
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Received sample lead");
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($_REQUEST, true));

		return $ajax_form;
	}
	
	/**
	 * Executes a POST request
	 */
	function executePost($input_form) {
		return $this->executeGet($input_form);
	}
	
	/**
	 * Executes a PUT request
	 */
	function executePut($input_form) {
		return $this->executeGet($input_form);
	}
}

?>