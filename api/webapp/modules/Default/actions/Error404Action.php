<?php
use Mojavi\Form\CommonForm;
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class Error404Action extends BasicRestAction
{

	/**
	 * Executes logic for this action
	 * @return void
	 */
	function execute() {
		$this->getErrors()->addError('error', '404: The page cannot be found: ' . $_REQUEST['module'] . '/' . $_REQUEST['action']);
		return parent::execute();
	}
	
	/**
	 * Returns the input form to use for this rest action
	 * @return CommonForm
	 */
	function getInputForm() {
		return new CommonForm();
	}

}

?>