<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Offer;
use Gun\Client;
use Gun\Flow;
use Gun\Vertical;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class RevenueReportAction extends BasicAction
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
        /* @var $revenue_report Gun\RevenueReport */
        $revenue_report = new \Gun\RevenueReport();
        $revenue_report->populate($_GET);

        /* @var $offer \Gun\Offer */
        $offer = new \Gun\Offer();
        $offer->setSort('name');
        $offer->setSord('ASC');
        $offer->setIgnorePagination(true);
        $offers = $offer->queryAll();

        /* @var $campaign \Gun\Campaign */
        $campaign = new \Gun\Campaign();
        $campaign->setSort('name');
        $campaign->setSord('ASC');
        $campaign->setIgnorePagination(true);
        $campaigns = $campaign->queryAll();

        $this->getContext()->getRequest()->setAttribute("revenue_report", $revenue_report);

        $this->getContext()->getRequest()->setAttribute("offers", $offers);
        $this->getContext()->getRequest()->setAttribute("campaigns", $campaigns);
        return View::SUCCESS;
    }
}

?>