<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Server;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ServerAction extends BasicAction
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
        if ($this->getContext()->getRequest()->getMethod() == Request::POST) {
            /* @var $server Flux\Server */
            $server = new Server();
            $server->populate($_POST);
            $server->update();

            $this->getContext()->getController()->redirect('/admin/server?_id=' . $server->getId());
        } else {
            /* @var $server Flux\Server */
            $server = new Server();
            $server->populate($_GET);
            $server->query();

            $this->getContext()->getRequest()->setAttribute("server", $server);
        }
        return View::SUCCESS;
    }
}

?>