<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Client;
use Gun\User;
use Gun\Offer;
use Gun\Campaign;
use Gun\Export;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ClientPaneExportAction extends BasicAction
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
        /* @var $client Gun\Client */
        $client = new Client();
        $client->populate($_REQUEST);
        $client->query();

        $this->getContext()->getRequest()->setAttribute("client", $client);
        return View::SUCCESS;
    }
}

?>