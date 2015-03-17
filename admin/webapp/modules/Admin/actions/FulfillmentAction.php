<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Fulfillment;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FulfillmentAction extends BasicAction
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
		$fulfillment = new \Flux\Fulfillment();
		$fulfillment->populate($_REQUEST);
		$fulfillment->query();
		
		/* @var $data_field Flux\DataField */
		$data_field = new \Flux\DataField();
		$data_field->setSort('name');
		$data_field->setSord('ASC');
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("fulfillment", $fulfillment);
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		
		return View::SUCCESS;
	}
}

?>