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
class LeadPaneDataFieldAction extends BasicAction
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
		
		if ($this->getContext()->getRequest()->hasParameter('data_field_id')) {
			// Find the data field we want to edit
			$data_field = new DataField();
			$data_field->setId($this->getContext()->getRequest()->getParameter('data_field_id', 0));
			$data_field->query();
			$value = $lead->getValue($data_field->getKeyName());		
			$data_fields = array(array('posting_url_dataField_id' => $data_field->getId(), 'posting_url_dataField_value' => $value));
			$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		}

		$this->getContext()->getRequest()->setAttribute("lead", $lead);
		return View::SUCCESS;
	}
}

?>