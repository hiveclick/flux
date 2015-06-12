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
class CampaignSearchAction extends BasicAction
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
		/* @var $campaign \Flux\Campaign */
		$campaign = new \Flux\Campaign();
		$campaign->populate($_REQUEST);
		
		/* @var $vertical \Flux\Vertical */
		$vertical = new \Flux\Vertical();
		$vertical->setSort('name');
		$vertical->setSord('ASC');
		$vertical->setIgnorePagination(true);
		$verticals = $vertical->queryAll();
		
		/* @var $client \Flux\Client */
		$client = new \Flux\Client();
		$client->setSort('name');
		$client->setSord('ASC');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();
		
		/* @var $offer \Flux\Offer */
		$offer = new \Flux\Offer();
		$offer->setSort('name');
		$offer->setSord('ASC');
		$offer->setIgnorePagination(true);
		$offers = $offer->queryAll();
		
		/* @var $traffic_source \Flux\TrafficSource */
		$traffic_source = new \Flux\TrafficSource();
		$traffic_source->setSort('name');
		$traffic_source->setSord('ASC');
		$traffic_source->setIgnorePagination(true);
		$traffic_sources = $traffic_source->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("campaign", $campaign);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		$this->getContext()->getRequest()->setAttribute("traffic_sources", $traffic_sources);
		$this->getContext()->getRequest()->setAttribute("verticals", $verticals);
		$this->getContext()->getRequest()->setAttribute("offers", $offers);
		return View::SUCCESS;
	}
}

?>