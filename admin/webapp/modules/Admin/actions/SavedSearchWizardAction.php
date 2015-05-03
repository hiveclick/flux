<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Server;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class SavedSearchWizardAction extends BasicAction
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
		/* @var $saved_search Flux\SavedSearch */
		$saved_search = new \Flux\SavedSearch();
		$saved_search->populate($_GET);
		if ($saved_search->getId() > 0) {
			$saved_search->query();	
		}
		
		/* @var $datafield \Flux\DataField */
		$data_field = new \Flux\DataField();
		$data_field->setSort('name');
		$data_field->setSord('asc');
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();
		
		/* @var $offer \Flux\Offer */
		$offer = new \Flux\Offer();
		$offer->setSort('name');
		$offer->setSord('asc');
		$offer->setIgnorePagination(true);
		$offers = $offer->queryAll();
		
		/* @var $client \Flux\Client */
		$client = new \Flux\Client();
		$client->setSort('name');
		$client->setSord('asc');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();
		
		/* @var $campaign \Flux\Campaign */
		$campaign = new \Flux\Campaign();
		$campaign->setSort('_id');
		$campaign->setSord('desc');
		$campaign->setItemsPerPage(100);
		$campaigns = $campaign->queryAll();
		
		/* @var $traffic_source \Flux\TrafficSource */
		$traffic_source = new \Flux\TrafficSource();
		$traffic_source->setSort('name');
		$traffic_source->setSord('asc');
		$traffic_source->setIgnorePagination(true);
		$traffic_sources = $traffic_source->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("offers", $offers);
		$this->getContext()->getRequest()->setAttribute("campaigns", $campaigns);
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		$this->getContext()->getRequest()->setAttribute("traffic_sources", $traffic_sources);

		$this->getContext()->getRequest()->setAttribute("saved_search", $saved_search);
		
		return View::SUCCESS;
	}
}

?>