<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class IndexAction extends BasicAction
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
		$today_rev = 0;
		$yesterday_rev = 0;
		$monthly_rev = 0;
		
		/* @var $revenue_report Flux\ReportClient */
		$report_client = new \Flux\ReportClient();
		$report_client->setStartDate(date('m/01/Y'));
		$report_client->setEndDate(date('m/t/Y'));
		$report_client->setIgnorePagination(true);
		$daily_rev_items = $report_client->queryAll(array(), true);
		
		/* @var $daily_rev_item \Flux\ReportClient */
		foreach ($daily_rev_items as $daily_rev_item) {	   
			$monthly_rev += $daily_rev_item->getRevenue();
			if (date('m/d/Y', $daily_rev_item->getReportDate()->sec) == date('m/d/Y')) {
				$today_rev += $daily_rev_item->getRevenue();
			}
			if (date('m/d/Y', $daily_rev_item->getReportDate()->sec) == date('m/d/Y', strtotime('yesterday'))) {
				$yesterday_rev += $daily_rev_item->getRevenue();
			}
		}
		
		$this->getContext()->getRequest()->setAttribute("today_revenue", $today_rev);
		$this->getContext()->getRequest()->setAttribute("yesterday_revenue", $yesterday_rev);
		$this->getContext()->getRequest()->setAttribute("monthly_revenue", $monthly_rev);

		return View::SUCCESS;
	}
	
	/**
	 * Indicates that this action requires security.
	 *
	 * @return bool true, if this action requires security, otherwise false.
	 */
	public function isSecure ()
	{
	
		return TRUE;
	
	}
}

?>