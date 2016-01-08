<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class GraphConversionByHourAction extends BasicRestAction
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
		parent::execute();
		return \Mojavi\View\View::SUCCESS;
	}

	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\SpyReport
	 */
	function getInputForm() {
		return new \Flux\Report\GraphConversionByHour();
	}

	/**
	 * Executes a GET request
	 */
	function executeGet($input_form) {
		// Handle GET Requests
		/* @var $ajax_form BasicAjaxForm */
		$ajax_form = new BasicAjaxForm();
		$input_form->compileReport();
		$ajax_form->setRecord($input_form);
		$ajax_form->setTotal($input_form->getTotal());
		$ajax_form->setPage($input_form->getPage());
		$ajax_form->setItemsPerPage($input_form->getItemsPerPage());
		return $ajax_form;
	}
}

?>