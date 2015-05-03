<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Campaign;
use Flux\Offer;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class LeadReportAction extends BasicAction
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
	    /* @var $split_queue \Flux\SplitQueue */
	    $split_queue = new \Flux\SplitQueue();
	    $split_queue->populate($_REQUEST);
	    
		/* @var $campaign \Flux\Campaign */
		$campaign = new \Flux\Campaign();
		$campaign->setSort('name');
		$campaign->setSord('ASC');
		$campaign->setIgnorePagination(true);
		$campaigns = $campaign->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("split_queue", $split_queue);
		$this->getContext()->getRequest()->setAttribute("campaigns", $campaigns);
		return View::SUCCESS;
	}
}

?>