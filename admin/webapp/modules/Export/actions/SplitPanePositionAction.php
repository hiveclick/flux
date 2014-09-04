<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Split;
use Gun\Offer;
use Gun\Vertical;
use Gun\Client;
use Gun\DataField;
use Gun\DomainGroup;
use Gun\ClientExport;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class SplitPanePositionAction extends BasicAction
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
            /* @var $split Gun\Split */
            $split = new Split();
            $split->populate($_POST);
            $split->update();

            /* @var $client Gun\Client */
            $client = new Client();
            $client->setSort('name');
            $client->setSord('ASC');
            $client->setIgnorePagination(true);
            $clients = $client->queryAll();

            $this->getContext()->getRequest()->setAttribute("split", $split);
            $this->getContext()->getRequest()->setAttribute("clients", $clients);
        } else {
            /* @var $split Gun\Split */
            $split = new Split();
            $split->populate($_GET);
            $split->query();

            /* @var $client Gun\Client */
            $client = new Client();
            $client->setSort('name');
            $client->setSord('ASC');
            $client->setIgnorePagination(true);
            $clients = $client->queryAll();

            /* @var $data_field Gun\DataField */
            $data_field = new DataField();
            $data_field->setSort('name');
            $data_field->setSord('ASC');
            $data_field->setIgnorePagination(true);
            $data_fields = $data_field->queryAll();

            /* @var $domain_group Gun\DomainGroup */
            $domain_group = new DomainGroup();
            $domain_group->setSort('name');
            $domain_group->setSord('ASC');
            $domain_group->setIgnorePagination(true);
            $domain_groups = $domain_group->queryAll();

            $this->getContext()->getRequest()->setAttribute("split", $split);
            $this->getContext()->getRequest()->setAttribute("clients", $clients);
            $this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
            $this->getContext()->getRequest()->setAttribute("domain_groups", $domain_groups);
        }
        return View::SUCCESS;
    }
}

?>