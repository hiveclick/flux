<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Flow;
use Flux\Offer;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class FlowPaneNodeAction extends BasicAction
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
        /* @var $flow Flux\Flow */
        $flow = new Flow();
        $flow->populate($_GET);
        $flow->query();
        
        $offer = new Offer();
        $offer->setSort('name');
        $offer->setIgnorePagination(true);
        $offers = $offer->queryAll();
        
        $flow_search = new Flow();
        $flow_search->setSort('name');
        $flow_search->setIgnorePagination(true);
        $flows = $flow_search->queryAll();
            
        $this->getContext()->getRequest()->setAttribute("flow", $flow);
        $this->getContext()->getRequest()->setAttribute("offers", $offers);
        $this->getContext()->getRequest()->setAttribute("flows", $flows);
        
        return View::SUCCESS;
    }
}

?>