<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Campaign;
use Flux\Client;
use Flux\Offer;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class CampaignWizardAction extends BasicAction
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
        if ($this->getContext()->getRequest()->getMethod() == Request::POST) {
            try {
                /* @var $campaign Flux\Campaign */
                $campaign = new Campaign();
                $campaign->populate($_POST);
                $campaign->insert();
                $this->getContext()->getController()->redirect('/campaign/campaign?_id=' . $campaign->getId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("campaign", $campaign);
            return View::SUCCESS;
        } else {
            /* @var $user Flux\Campaign */
            $campaign = new Campaign();
            $campaign->populate($_GET);
            
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