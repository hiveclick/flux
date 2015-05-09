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
class ReconcileLeadPayoutWizardAction extends BasicAction
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
		/* @var $report_lead Flux\ReportLead */
		$report_lead = new \Flux\ReportLead();
		$report_lead->populate($_GET);
		$report_lead->query();
		
		/* @var $client \Flux\Client */
		$client = new \Flux\Client();
		$client->setSort('name');
		$client->setSord('asc');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();

		$this->getContext()->getRequest()->setAttribute("report_lead", $report_lead);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		
		return View::SUCCESS;
	}
}

?>