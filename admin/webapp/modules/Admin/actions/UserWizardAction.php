<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\User;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class UserWizardAction extends BasicAction
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
                /* @var $user Flux\User */
                $user = new User();
                $user->populate($_POST);
                $user->insert();
                $this->getContext()->getController()->redirect('/admin/user?_id=' . $user->getId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("user", $user);
            return View::SUCCESS;
        } else {
            /* @var $user Flux\User */
            $user = new User();
            $user->populate($_GET);
            
            $client = new Client();
            $clients = $client->queryAll();
            
            $this->getContext()->getRequest()->setAttribute("user", $user);
            $this->getContext()->getRequest()->setAttribute("clients", $clients);
        }
        return View::SUCCESS;
    }
}

?>