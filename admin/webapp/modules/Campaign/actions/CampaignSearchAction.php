<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Campaign;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
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
        /* @var $user Gun\Campaign */
        $campaign = new Campaign();
        $campaign->populate($_REQUEST);
        
        $offer = new \Gun\Offer();
        $offer->setSort('name');
        $offer->setIgnorePagination(true);
        $offers = $offer->queryAll();

        $this->getContext()->getRequest()->setAttribute("campaign", $campaign);
        $this->getContext()->getRequest()->setAttribute("offers", $offers);
        return View::SUCCESS;
    }
}

?>