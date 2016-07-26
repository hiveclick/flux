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
class SavedReportAction extends BasicRestAction
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
	 * @return \Flux\SpyReport
	 */
	function getInputForm() {
		return new \Flux\SavedReport();
	}

	/**
	 * Executes the post logic
	 */
	function executePost($input_form) {
		if (\MongoId::isValid($input_form->getId())) {
			return parent::executePut($input_form);
		} else {
			return parent::executePost($input_form);
		}
	}
}

?>