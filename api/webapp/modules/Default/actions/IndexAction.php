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
		$this->getErrors()->addError('error', 'You must access this api from an approved page');
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