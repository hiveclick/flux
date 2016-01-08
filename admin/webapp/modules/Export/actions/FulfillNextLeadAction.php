<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Lead;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FulfillNextLeadAction extends BasicAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 *
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		/* @var $lead_split Flux\LeadSplit */
		$lead_split = new \Flux\LeadSplit();
		$lead_split->populate($_REQUEST);
		$lead_split->query();
		
		$lead_split->setDisposition(\Flux\LeadSplit::DISPOSITION_FULFILLED);
		$lead_split->setErrorMessage('');
		$lead_split->setIsError(false);
		
		$lead_split_attempt = new \Flux\LeadSplitAttempt();
		$lead_split_attempt->setAttemptTime(new \MongoDate());
		$lead_split_attempt->setResponse($arg0);
		$lead_split_attempt->setIsError(true);
		
		$lead_split->update();
		
		$this->getContext()->getRequest()->setAttribute("split_queue", $split_queue);
		return View::SUCCESS;
	}
}

?>