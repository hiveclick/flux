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
		/* @var $offer Flux\SplitQueue */
		$split_queue = new \Flux\SplitQueue();
		$split_queue->populate($_REQUEST);
		
		$criteria = array();
		$criteria['split.split_id'] = array('$in' => $split_queue->getSplitIdArray());
		$criteria['disposition'] = array('$in' => array(\Flux\SplitQueue::DISPOSITION_UNFULFILLED, \Flux\SplitQueue::DISPOSITION_PENDING));
		
		$split_queue = $split_queue->query($criteria, false);
		
		$this->getContext()->getRequest()->setAttribute("split_queue", $split_queue);
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