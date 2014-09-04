<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Offer;
use Gun\Client;
use Gun\Flow;
use Gun\Vertical;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class OfferWizardAction extends BasicAction
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
                /* @var $offer Gun\Offer */
                $offer = new Offer();
                $offer->populate($_POST);
                $offer->insert();

                $this->getContext()->getController()->redirect('/offer/offer?_id=' . $offer->getId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("offer", $offer);
            return View::SUCCESS;
        } else {
            /* @var $offer Gun\Offer */
            $offer = new Offer();
            $offer->populate($_GET);

            /* @var $client Gun\Client */
            $client = new Client();
            $client->setSort('name');
            $client->setIgnorePagination(true);
            $clients = $client->queryAll();

            /* @var $flow Gun\Flow */
            $flow = new Flow();
            $flow->setSort('name');
            $flow->setIgnorePagination(true);
            $flows = $flow->queryAll();

            /* @var $vertical Gun\Vertical */
            $vertical = new Vertical();
            $vertical->setSort('name');
            $vertical->setSord('ASC');
            $vertical->setIgnorePagination(true);
            $verticals = $vertical->queryAll();

            $this->getContext()->getRequest()->setAttribute("offer", $offer);
            $this->getContext()->getRequest()->setAttribute("clients", $clients);
            $this->getContext()->getRequest()->setAttribute("flows", $flows);
            $this->getContext()->getRequest()->setAttribute("verticals", $verticals);
        }
        return View::SUCCESS;
    }
}