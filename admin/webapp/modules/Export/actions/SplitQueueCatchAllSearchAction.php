<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;

use Flux\Split;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class SplitQueueCatchAllSearchAction extends BasicAction
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
		/* @var $split Flux\Split */
		$split_queue = new \Flux\SplitQueue();
		$split_queue->populate($_REQUEST);
	    if (empty($split_queue->getDispositionArray())) {
		    $split_queue->setDispositionArray(array(\Flux\SplitQueue::DISPOSITION_UNFULFILLED, \Flux\SplitQueue::DISPOSITION_PENDING));
		}
		
		/* @var $split Flux\Split */
		$split = new \Flux\Split();
		$split->setSort('name');
		$split->setSord('asc');
		$split->setIgnorePagination(true);
		$splits = $split->queryAll();
		
		/* @var $vertical \Flux\Vertical */
		$vertical = new \Flux\Vertical();
		$vertical->setSort('name');
		$vertical->setSord('ASC');
		$vertical->setIgnorePagination(true);
		$verticals = $vertical->queryAll();
		
		$offer = new \Flux\Offer();
		$offer->setSort('name');
		$offer->setSord('asc');
		$offer->setIgnorePagination(true);
		$offers = $offer->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("split_queue", $split_queue);
		$this->getContext()->getRequest()->setAttribute("splits", $splits);
		$this->getContext()->getRequest()->setAttribute("offers", $offers);
		$this->getContext()->getRequest()->setAttribute("verticals", $verticals);
		 
		return View::SUCCESS;
	}
}

?>