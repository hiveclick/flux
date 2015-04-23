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
class TrafficSourceWizardAction extends BasicAction
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
		/* @var $traffic_source \Flux\TrafficSource */
		$traffic_source = new \Flux\TrafficSource();
		$traffic_source->populate($_REQUEST);
		
		if ($traffic_source->getId() > 0) {
			$traffic_source->query();	
		}
		
		$client = new \Flux\Client();
		$client->setSort('name');
		$client->setSord('ASC');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("traffic_source", $traffic_source);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		
		return View::SUCCESS;
	}
}

?>