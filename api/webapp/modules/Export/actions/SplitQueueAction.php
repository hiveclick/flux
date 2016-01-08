<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class SplitQueueAction extends BasicRestAction
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
		if ($this->getContext()->getRequest()->hasParameter('split_id')) {
			return new \Flux\SplitQueue($this->getContext()->getRequest()->getParameter('split_id'));
		}
		return new \Flux\SplitQueue();
	}
	
	/**
	 * Handle a POST the same as a GET
	 */
	function executePost($input_form) {
		$ret_val = $this->executeGet($input_form);
		return $ret_val;
	}
}

?>