<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Client;
use Flux\Flow;
use Flux\Vertical;
use Mojavi\Logging\LoggerManager;
use Flux\Server;
use Flux\Campaign;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferAction extends BasicAction
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
		if ($this->getContext()->getRequest()->getMethod() == Request::POST) {
			/* @var $offer Flux\Offer */
			$offer = new Offer();
			$offer->populate($_POST);
			$offer->update();
			
			$this->getContext()->getController()->redirect('/offer/offer?_id=' . $offer->getId());
		} else {
			/* @var $offer Flux\Offer */
			$offer = new Offer();
			$offer->populate($_GET);
			$offer->query();
			
			/* @var $client Flux\Client */
			$client = new Client();
			$client->setSort('name');
			$client->setIgnorePagination(true);
			$clients = $client->queryAll();
			
			/* @var $flow Flux\Flow */
			$flow = new Flow();
			$flow->setSort('name');
			$flow->setIgnorePagination(true);
			$flows = $flow->queryAll();
			
			/* @var $vertical Flux\Vertical */
			$vertical = new Vertical();
			$vertical->setSort('name');
			$vertical->setSord('ASC');
			$vertical->setIgnorePagination(true);
			$verticals = $vertical->queryAll();
			
			/* @var $vertical Flux\Campaign */
			$campaign = new Campaign();
			$campaign->setSort('name');
			$campaign->setSord('ASC');
			$campaign->setOfferIdArray(array(0 => $offer->getId()));
			$campaign->setIgnorePagination(true);
			$campaigns = $campaign->queryAll();
			
			$this->getContext()->getRequest()->setAttribute("offer", $offer);
			$this->getContext()->getRequest()->setAttribute("clients", $clients);
			$this->getContext()->getRequest()->setAttribute("campaigns", $campaigns);
			$this->getContext()->getRequest()->setAttribute("flows", $flows);
			$this->getContext()->getRequest()->setAttribute("verticals", $verticals);
		}
		return View::SUCCESS;
	}
}