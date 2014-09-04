<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Client;
use Gun\ClientExport;
use Gun\DataField;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ClientExportPaneMapAction extends BasicAction
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
        /* @var $offer Gun\ClientExport */
        $client_export = new ClientExport();
        $client_export->populate($_REQUEST);
        $client_export->query();
        
        /* @var $data_field Gun\DataField */
        $data_field = new DataField();
        $data_field->setStorageTypeArray(array(0 => DataField::DATA_FIELD_STORAGE_TYPE_TRACKING));
        $data_field->setSort('name');
        $data_field->setSord('ASC');
        $data_field->setIgnorePagination(true);
        $tracking_data_fields = $data_field->queryAll();
        
        /* @var $data_field Gun\DataField */
        $data_field = new DataField();
        $data_field->setStorageTypeArray(array(0 => DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT));
        $data_field->setSort('name');
        $data_field->setSord('ASC');
        $data_field->setIgnorePagination(true);
        $default_data_fields = $data_field->queryAll();
        
        /* @var $data_field Gun\DataField */
        $data_field = new DataField();
        $data_field->setStorageTypeArray(array(0 => DataField::DATA_FIELD_STORAGE_TYPE_EVENT));
        $data_field->setSort('name');
        $data_field->setSord('ASC');
        $data_field->setIgnorePagination(true);
        $event_data_fields = $data_field->queryAll();
        
        $this->getContext()->getRequest()->setAttribute("client_export", $client_export);
        $this->getContext()->getRequest()->setAttribute("tracking_data_fields", $tracking_data_fields);
        $this->getContext()->getRequest()->setAttribute("default_data_fields", $default_data_fields);
        $this->getContext()->getRequest()->setAttribute("event_data_fields", $event_data_fields);
        return View::SUCCESS;
    }
}

?>