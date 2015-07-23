<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Lead;
use Flux\DataField;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class LeadPaneCompleteAction extends BasicAction
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
		/* @var $offer Flux\Lead */
		$lead = new Lead();
		$lead->populate($_REQUEST);
		$lead->query();
		
		// @var $data_field \Flux\DataField */
		$data_field = new DataField();
		$data_field->setIgnorePagination(true);
		$data_field->setSort('name');
		$data_field->setSord('asc');
		$data_fields = $data_field->queryAll();
		
		
		if ($this->getContext()->getRequest()->hasParameter('data_field_id')) {
			// Find the data field we want to edit
			$data_field = new DataField();
			$data_field->setId($this->getContext()->getRequest()->getParameter('data_field_id', 0));
			$data_field->query();
			
			$data_field_set = $data_field->getDataFieldSet();
			array_walk($data_field_set, function(&$value) { if ($value instanceof \Flux\Base\DataFieldSet) { $value = $value->toArray(); }});
			
			$value = $lead->getValue($data_field->getKeyName());		
			$selected_data_fields = array(0 => array('posting_url_data_field_id' => $data_field->getId(), 'posting_url_data_field_value' => $value, 'posting_url_data_field_set' => $data_field_set));
			$this->getContext()->getRequest()->setAttribute("selected_data_fields", $selected_data_fields);
		}

		$this->getContext()->getRequest()->setAttribute("lead", $lead);
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		return View::SUCCESS;
	}
}

?>