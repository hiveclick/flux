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
class LeadSplitPendingTodaySearchAction extends BasicAction
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
		$lead_split->setDateRange(\Mojavi\Form\DateRangeForm::DATE_RANGE_LAST_2_DAYS);
		if (empty($lead_split->getDispositionArray())) {
			$lead_split->setDispositionArray(array(\Flux\LeadSplit::DISPOSITION_UNFULFILLED, \Flux\LeadSplit::DISPOSITION_PENDING, \Flux\LeadSplit::DISPOSITION_PROCESSING));
		}

		/* @var $split Flux\Split */
		$split = new \Flux\Split();
		$split->setSort('name');
		$split->setSord('asc');
		$split->setIgnorePagination(true);
		$splits = $split->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("lead_split", $lead_split);
		$this->getContext()->getRequest()->setAttribute("splits", $splits);
		return View::SUCCESS;
	}
}

?>