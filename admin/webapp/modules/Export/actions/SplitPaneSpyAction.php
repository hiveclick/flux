<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;

use Gun\Split;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class SplitPaneSpyAction extends BasicAction
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
        /* @var $split Gun\Split */
        $split = new Split();
        $split->populate($_REQUEST);
        $split->query();
        
        $datafield = new \Gun\DataField();
        $datafield->setIgnorePagination(true);
        $datafields = $datafield->queryAll();
        
        $offer = new \Gun\Offer();
        $offer->setSort('name');
        $offer->setIgnorePagination(true);
        $offers = $offer->queryAll();
        
        $this->getContext()->getRequest()->setAttribute("split", $split);
        $this->getContext()->getRequest()->setAttribute("datafields", $datafields);
        $this->getContext()->getRequest()->setAttribute("offers", $offers);
         
        return View::SUCCESS;
    }
}

?>