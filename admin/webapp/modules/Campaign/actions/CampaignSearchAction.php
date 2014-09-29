<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Campaign;
use Flux\Offer;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class CampaignSearchAction extends BasicAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute any application/business logic for this action.
     *
     * @return mixed - A string containing the view name associated with this action
     */
    public function execute ()
    {
        /* @var $user Flux\Campaign */
        $campaign = new Campaign();
        $campaign->populate($_REQUEST);
        
        $offer = new Offer();
        $offer->setSort('name');
        $offer->setSord('ASC');
        $offer->setIgnorePagination(true);
        $offers = $offer->queryAll();
        
        /* @var $client \Flux\Client */
        $client = new Client();
        $client->setSort('name');
        $client->setSord('ASC');
        $client->setIgnorePagination(true);
        $clients = $client->queryAll();
        
        $this->getContext()->getRequest()->setAttribute("campaign", $campaign);
        $this->getContext()->getRequest()->setAttribute("offers", $offers);
        $this->getContext()->getRequest()->setAttribute("clients", $clients);
        return View::SUCCESS;
    }
}

?>