<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Offer;
use Gun\Campaign;
use Gun\SpyReport;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class OfferPaneSpyAction extends BasicAction
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
        /* @var $offer Gun\Offer */
        $offer = new Offer();
        $offer->populate($_REQUEST);
        $offer->query();
        
        $spy_report = new SpyReport();
        
        $datafield = new \Gun\DataField();
        $datafield->setIgnorePagination(true);
        $datafields = $datafield->queryAll();
        
        $this->getContext()->getRequest()->setAttribute("offer", $offer);
        $this->getContext()->getRequest()->setAttribute("spy_report", $spy_report);
        $this->getContext()->getRequest()->setAttribute("datafields", $datafields);
         
        return View::SUCCESS;
    }
}

?>