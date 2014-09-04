<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Server;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ServerWizardAction extends BasicAction
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
            try {
                /* @var $server Gun\Server */
                $server = new Server();
                $server->populate($_POST);
                $server->insert();
                $this->getContext()->getController()->redirect('/admin/server?_id=' . $server->getId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("server", $server);
            return View::SUCCESS;
        } else {
            /* @var $server Gun\Server */
            $server = new Server();
            $server->populate($_GET);

            $this->getContext()->getRequest()->setAttribute("server", $server);
        }
        return View::SUCCESS;
    }
}

?>