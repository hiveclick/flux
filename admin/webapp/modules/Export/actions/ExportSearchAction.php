<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Export;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ExportSearchAction extends BasicAction
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
        /* @var $export Gun\Export */
        $export = new Export();
        $export->populate($_REQUEST);
        
        $split = new \Gun\Split();
        $split->setIgnorePagination(true);
        $splits = $split->queryAll();
        
        $client_export = new \Gun\ClientExport();
        $client_export->setIgnorePagination(true);
        $client_exports = $client_export->queryAll();
        

        $this->getContext()->getRequest()->setAttribute("export", $export);
        $this->getContext()->getRequest()->setAttribute("splits", $splits);
        $this->getContext()->getRequest()->setAttribute("client_exports", $client_exports);
        return View::SUCCESS;
    }
}

?>