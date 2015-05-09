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
class DashboardAction extends BasicAction
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
		/* @var $report_lead \Flux\ReportLead */
		$report_lead = new \Flux\ReportLead();
		$report_lead->setSort('name');
		$report_lead->setSord('ASC');
		$report_lead->setIgnorePagination(true);
		$report_leads = $report_lead->queryAll();
		
		return View::SUCCESS;
	}
}

?>