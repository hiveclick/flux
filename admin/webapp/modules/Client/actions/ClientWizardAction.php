<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ClientWizardAction extends BasicAction
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
                /* @var $client Gun\Client */
                $client = new Client();
                $client->populate($_POST);
                $client->insert();
                $this->getContext()->getController()->redirect('/client/client?_id=' . $client->getId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("client", $client);
            return View::SUCCESS;
        } else {
            /* @var $client Gun\Client */
            $client = new Client();
            $client->populate($_GET);
            
            $this->getContext()->getRequest()->setAttribute("client", $client);
        }
        return View::SUCCESS;
    }
}

?>