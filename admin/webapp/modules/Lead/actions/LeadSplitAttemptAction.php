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
class LeadSplitAttemptAction extends BasicAction
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
		
		$attempts = $lead_split->getAttempts();
		$index = $this->getContext()->getRequest()->getParameter('index');
		
		/* @var $lead_split_attempt Flux\LeadSplitAttempt */
		$lead_split_attempt = new \Flux\LeadSplitAttempt();
		
		if (isset($attempts[$index])) {
			$lead_split_attempt->populate($attempts[$index]);
		}
		$lead_split_attempt->setAttemptIndex($index);

		$this->getContext()->getRequest()->setAttribute("lead_split_attempt", $lead_split_attempt);
		return View::SUCCESS;
	}
}

?>