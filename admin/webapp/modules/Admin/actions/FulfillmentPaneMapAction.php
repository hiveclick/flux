<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Fulfillment;
use Flux\DataField;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FulfillmentPaneMapAction extends BasicAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 *
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		/* @var $fulfillment Flux\Fulfillment */
		$fulfillment = new Fulfillment();
		$fulfillment->populate($_REQUEST);
		$fulfillment->query();
		
		/* @var $data_field Flux\DataField */
		$data_field = new DataField();
		$data_field->setStorageTypeArray(array(0 => DataField::DATA_FIELD_STORAGE_TYPE_TRACKING));
		$data_field->setSort('name');
		$data_field->setSord('ASC');
		$data_field->setIgnorePagination(true);
		$tracking_data_fields = $data_field->queryAll();
		
		/* @var $data_field Flux\DataField */
		$data_field = new DataField();
		$data_field->setStorageTypeArray(array(0 => DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT));
		$data_field->setSort('name');
		$data_field->setSord('ASC');
		$data_field->setIgnorePagination(true);
		$default_data_fields = $data_field->queryAll();
		
		/* @var $data_field Flux\DataField */
		$data_field = new DataField();
		$data_field->setStorageTypeArray(array(0 => DataField::DATA_FIELD_STORAGE_TYPE_EVENT));
		$data_field->setSort('name');
		$data_field->setSord('ASC');
		$data_field->setIgnorePagination(true);
		$event_data_fields = $data_field->queryAll();
		
		/* @var $data_field Flux\DataField */
		$data_field = new DataField();
		$data_field->setStorageTypeArray(array(0 => DataField::DATA_FIELD_STORAGE_TYPE_DERIVED));
		$data_field->setSort('name');
		$data_field->setSord('ASC');
		$data_field->setIgnorePagination(true);
		$derived_data_fields = $data_field->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("fulfillment", $fulfillment);
		$this->getContext()->getRequest()->setAttribute("tracking_data_fields", $tracking_data_fields);
		$this->getContext()->getRequest()->setAttribute("default_data_fields", $default_data_fields);
		$this->getContext()->getRequest()->setAttribute("event_data_fields", $event_data_fields);
		$this->getContext()->getRequest()->setAttribute("derived_data_fields", $derived_data_fields);
		return View::SUCCESS;
	}
}

?>