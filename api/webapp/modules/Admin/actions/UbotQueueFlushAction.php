<?php
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class UbotQueueFlushAction extends BasicRestAction
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
	 * @return \Flux\PingbackKeywordQueue
	 */
	function getInputForm() {
		return new \Flux\UbotQueue();
	}

	/**
	 * Posts new keywords into the queue
	 * @return AjaxForm
	 */
	function executePost($input_form) {
		$ajax_form = new \Mojavi\Form\AjaxForm();
		$rows_affected = $input_form->flushQueue();
		$ajax_form->setRecord($input_form);
		$ajax_form->setRowsAffected($rows_affected);
		return $ajax_form;
	}
}
