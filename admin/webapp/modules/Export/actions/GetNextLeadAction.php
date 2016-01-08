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
class GetNextLeadAction extends BasicAction
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
		/* @var $offer Flux\LeadSplit */
		$lead_split = new \Flux\LeadSplit();
		$lead_split->populate($_REQUEST);
		
		$criteria = array();
		if (\MongoId::isValid($lead_split->getLead()->getLeadId())) {
			$criteria['lead.lead_id'] = $lead_split->getLead()->getLeadId();
			$criteria['split.split_id'] = array('$in' => $lead_split->getSplitIdArray());
		} else {
			$criteria['split.split_id'] = array('$in' => $lead_split->getSplitIdArray());
			$criteria['disposition'] = array('$in' => array(\Flux\LeadSplit::DISPOSITION_UNFULFILLED));
		}
		
		$lead_split = $lead_split->findAndModify($criteria, array('$set' => array('disposition' => \Flux\LeadSplit::DISPOSITION_PROCESSING)), null, array('new' => true), true);
		
		$this->getContext()->getRequest()->setAttribute("lead_split", $lead_split);
		return View::SUCCESS;
	}
	
	/**
	 * Returns whether the user needs to be logged in or not
	 * @return boolean
	 */
	function isSecure() {
		return true;
	}
}

?>