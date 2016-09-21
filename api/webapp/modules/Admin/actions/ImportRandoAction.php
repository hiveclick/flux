<?php
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ImportRandoAction extends BasicRestAction
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
	 * @return \Flux\Rando
	 */
	function getInputForm() {
		return new \Flux\Rando();
	}

	/**
	 * Imports the rando data
	 * @param $input_form \Flux\Rando
	 * @return \Mojavi\Form\AjaxForm
	 */
	function executePost($input_form) {
		$ajax = new \Mojavi\Form\AjaxForm();
		$rows_affected = $input_form->import();
		$ajax->setRecord($input_form);
		$ajax->setRowsAffected($rows_affected);
		return $ajax;
	}
}

?>