<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Campaign;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class CampaignPaneEditAction extends BasicAction
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
			/* @var $user Flux\Campaign */
			$campaign = new Campaign();
			$campaign->populate($_POST);
			$campaign->update();
			
			$this->getContext()->getController()->redirect('/campaign/campaign?_id=' . $campaign->getId());
		} else {
			/* @var $user Flux\Campaign */
			$campaign = new Campaign();
			$campaign->populate($_GET);
			$campaign->query();
			
			$client = new Client();
			$client->setSort('name');
			$client->setSord('ASC');
			$client->setIgnorePagination(true);
			$clients = $client->queryAll();
			
			$offer = new Offer();
			$offer->setSort('name');
			$offer->setSord('ASC');
			$offer->setIgnorePagination(true);
			$offers = $offer->queryAll();
			
			$this->getContext()->getRequest()->setAttribute("campaign", $campaign);
			$this->getContext()->getRequest()->setAttribute("clients", $clients);
			$this->getContext()->getRequest()->setAttribute("offers", $offers);
		}
		return View::SUCCESS;
	}
}

?>