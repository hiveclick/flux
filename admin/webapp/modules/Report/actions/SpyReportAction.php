<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\SpyReport;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class SpyReportAction extends BasicAction
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
        /* @var $spy_report Gun\SpyReport */
        $spy_report = new SpyReport();

        $spy_columns = SpyReport::getColumns();

        $offer = new \Gun\Offer();
        $offer->setIgnorePagination(true);
        $offer->setSort('name');
        $offer->setSord('ASC');
        $offers = $offer->queryAll();

        $this->getContext()->getRequest()->setAttribute("offers", $offers);
        $this->getContext()->getRequest()->setAttribute("spy_report", $spy_report);
        $this->getContext()->getRequest()->setAttribute("spy_columns", $spy_columns);
        return View::SUCCESS;
    }
}

?>