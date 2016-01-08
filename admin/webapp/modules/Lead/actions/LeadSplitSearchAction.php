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
class LeadSplitSearchAction extends BasicAction
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
		if (empty($lead_split->getDispositionArray())) {
			$lead_split->setDispositionArray(array(\Flux\LeadSplit::DISPOSITION_UNFULFILLED, \Flux\LeadSplit::DISPOSITION_PENDING));
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
		
		$this->getContext()->getRequest()->setAttribute("lead_split", $lead_split);
		$this->getContext()->getRequest()->setAttribute("splits", $splits);
		$this->getContext()->getRequest()->setAttribute("offers", $offers);
		$this->getContext()->getRequest()->setAttribute("verticals", $verticals);
		return View::SUCCESS;
	}
}

?>