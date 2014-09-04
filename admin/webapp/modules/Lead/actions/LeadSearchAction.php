<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Offer;
use Gun\Lead;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class LeadSearchAction extends BasicAction
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
        /* @var $offer Gun\Lead */
        $lead = new Lead();
        $lead->populate($_REQUEST);

        $datafield = new \Gun\DataField();
        $datafield->setIgnorePagination(true);
        $datafields = $datafield->queryAll();
        
        $offer = new \Gun\Offer();
        $offer->setSort('name');
        $offer->setIgnorePagination(true);
        $offers = $offer->queryAll();

        $this->getContext()->getRequest()->setAttribute("lead", $lead);
        $this->getContext()->getRequest()->setAttribute("offers", $offers);
        $this->getContext()->getRequest()->setAttribute("datafields", $datafields);
        return View::SUCCESS;
    }
}

?>