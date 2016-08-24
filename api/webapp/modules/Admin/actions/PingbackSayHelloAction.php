<?php
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class PingbackSayHelloAction extends BasicRestAction
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
	 * @return \Flux\Pingback
	 */
	function getInputForm() {
		return new \Flux\Pingback();
	}

	/**
	 * Attempts to say hello
	 */
	function executeGet($input_form) {
		$ajax_form = new \Mojavi\Form\AjaxForm();
		$input_form->sayHello();
		$ajax_form->setRecord($input_form);
		return $ajax_form;
	}
}

?>