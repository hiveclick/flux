<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Campaign;
use Flux\Export;
use Flux\ExportQueue;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ExportPaneSpyAction extends BasicAction
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
        /* @var $offer Flux\Export */
        $export = new Export();
        $export->populate($_REQUEST);
        $export->query();
        
        /* @var $offer Flux\ExportQueue */
        $export_queue = new ExportQueue($export->getId());
        $export_queue->populate($_REQUEST);
        
        $this->getContext()->getRequest()->setAttribute("export", $export);
        $this->getContext()->getRequest()->setAttribute("export_queue", $export_queue);
         
        return View::SUCCESS;
    }
}

?>