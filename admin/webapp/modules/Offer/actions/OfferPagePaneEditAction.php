<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Offer;
use Gun\Client;
use Gun\Flow;
use Gun\Vertical;
use Mojavi\Logging\LoggerManager;
use Gun\OfferPage;
use Gun\Server;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class OfferPagePaneEditAction extends BasicAction
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
        /* @var $offer_page Gun\OfferPage */
        $offer_page = new OfferPage();
        $offer_page->populate($_GET);
        $offer_page->query();
        
        /* @var $server Gun\Server */
        $server = new Server();
        $server->setIgnorePagination(true);
        $servers = $server->queryAll();

        $this->getContext()->getRequest()->setAttribute("offer_page", $offer_page);
        $this->getContext()->getRequest()->setAttribute("servers", $servers);

        return View::SUCCESS;
    }
}