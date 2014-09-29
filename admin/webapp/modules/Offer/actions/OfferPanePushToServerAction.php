<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Campaign;
use Flux\Server;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class OfferPanePushToServerAction extends BasicAction
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
        
        /* @var $offer Flux\Offer */
        $input_form = new Offer();
        $input_form->populate($_REQUEST);
        
        /* @var $offer Flux\Offer */
        $offer = new Offer();
        $offer->setId($input_form->getId());
        $offer->query();
        
        $offer->setFolderName($input_form->getFolderName());
        $offer->setDomainName($input_form->getDomainName());
        $offer->setDocrootDir($input_form->getDocrootDir());
        
        /* @var $server Flux\Server */
        $server = new Server();
        $server->setSort('name');
        $server->setSord('ASC');
        $server->setIgnorePagination(true);
        $servers = $server->queryAll();

        $this->getContext()->getRequest()->setAttribute("offer", $offer);
        $this->getContext()->getRequest()->setAttribute("servers", $servers);

        return View::SUCCESS;
    }
}

?>