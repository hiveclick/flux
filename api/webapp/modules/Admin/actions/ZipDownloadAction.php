<?php
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ZipDownloadAction extends BasicRestAction
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
	 * @return \Flux\Vertical
	 */
	function getInputForm() {
		return new \Flux\Zip();
	}
	
	/**
	 * Executes a POST request
	 * @return \Mojavi\Form\AjaxForm
	 */
	function executeGet($input_form) {
		// Handle POST Requests
		$ajax_form = new \Mojavi\Form\AjaxForm();
		// Download the Zip file
		try {
			set_time_limit(0);
			$filename = $input_form->downloadZipcodeFile();
			$ajax_form->setInsertId(1);
			$ajax_form->setRowsAffected(1);
		} catch (\Exception $e) {
			$this->getErrors()->addError('error', $e->getMessage());
		}

		$ajax_form->setRecord($input_form);
		
		return $ajax_form;
	}
}

?>