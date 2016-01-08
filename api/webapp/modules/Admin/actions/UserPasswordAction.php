<?php
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class UserPasswordAction extends BasicRestAction
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
	 * @return \Flux\User
	 */
	function getInputForm() {
		return new \Flux\User();
	}
	
	/**
	 * Executes a POST request
	 * @return \Mojavi\Form\BasicAjaxForm
	 */
	function executePost($input_form) {
		// Handle POST Requests
		$ajax_form = new \Mojavi\Form\BasicAjaxForm();
		
		$rows_affected = $input_form->updatePassword();
		if (isset($rows_affected['n'])) {
			$ajax_form->setRowsAffected($rows_affected['n']);
		}
		$ajax_form->setRecord($input_form);
		return $ajax_form;
	}
}

?>