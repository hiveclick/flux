<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Client;
use Flux\ClientExport;
use Flux\DataField;
use Flux\ClientExportMap;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ClientExportPaneMapOptionsModalAction extends BasicAction
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
        /* @var $offer Flux\ClientExportMap */
        $client_export_map = new ClientExportMap();
        $client_export_map->populate($_REQUEST);
        
        $this->getContext()->getRequest()->setAttribute("client_export_map", $client_export_map);
        return View::SUCCESS;
    }
}

?>