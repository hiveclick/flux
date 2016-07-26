<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ClientExportSchedulingAction extends BasicRestAction
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
		return new \Flux\ClientExport();
	}
	
	/**
	 * Executes a PUT request
	 */
	function executePut($input_form) {
		/* @var $export_form \Flux\ClientExport */
		$export_form = new \Flux\ClientExport();
		$export_form->setId($input_form->getId());
		$export_form->flushSchedule();
		
		return parent::executePut($input_form);
	}
}

?>