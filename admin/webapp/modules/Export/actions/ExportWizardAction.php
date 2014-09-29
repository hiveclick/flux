<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Export;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ExportWizardAction extends BasicAction
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
                /* @var $export Flux\Export */
                $export = new Export();
                $export->populate($_POST);
                $export->insert();
                $this->getContext()->getController()->redirect('/export/export?_id=' . $export->getId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("export", $export);
            return View::SUCCESS;
        } else {
            /* @var $export Flux\Export */
            $export = new Export();
            $export->populate($_GET);
            
            /* @var $client Flux\Client */
            $client = new Client();
            $client->setSort('name');
            $client->setIgnorePagination(true);
            $clients = $client->queryAll();
            
            $this->getContext()->getRequest()->setAttribute("export", $export);
            $this->getContext()->getRequest()->setAttribute("clients", $clients);
        }
        return View::SUCCESS;
    }
}

?>