<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Flow;
use Gun\Offer;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class FlowAction extends BasicAction
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
            /* @var $flow Gun\Flow */
            $flow = new Flow();
            $flow->populate($_POST);
            $flow->update();
            
            $flow->query();
            
            $this->getContext()->getRequest()->setAttribute("flow", $flow);
        } else {
            /* @var $flow Gun\Flow */
            $flow = new Flow();
            $flow->populate($_GET);
            $flow->query();
            
            $offer = new Offer();
            $offer->setSort('name');
            $offer->setIgnorePagination(true);
            $offer->setStatus(Offer::OFFER_STATUS_ACTIVE);
            $offers = $offer->queryAll();
            
            $this->getContext()->getRequest()->setAttribute("flow", $flow);
            $this->getContext()->getRequest()->setAttribute("offer", $offers);
        }
        return View::SUCCESS;
    }
}

?>