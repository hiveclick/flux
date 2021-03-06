<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\CommonForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class IndexAction extends BasicRestAction
{
	/**
	 * Executes logic for this action
	 * @return void
	 */
	function execute() {
		$ajax_form = new \Mojavi\Form\AjaxForm();
		$this->getErrors()->addError('error', 'You must access this api from an approved page');
		$this->getRequest()->setAttribute('ajax_form', $ajax_form);
		return View::SUCCESS;
	}
	
	/**
	 * Returns the input form to use for this rest action
	 * @return \MongoForm
	 */
	function getInputForm() {
		return null;
	}
}

?>