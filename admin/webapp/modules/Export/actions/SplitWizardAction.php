<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Split;
use Flux\Offer;
use Flux\Fulfillment;
use Flux\DomainGroup;
use Flux\DataField;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class SplitWizardAction extends BasicAction
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
		/* @var $split Flux\Split */
		$split = new Split();
		$split->populate($_GET);

		/* @var $offer Flux\Offer */
		$offer = new Offer();
		$offer->setSort('name');
		$offer->setSord('asc');
		$offer->setIgnorePagination(true);
		$offers = $offer->queryAll();
		
		/* @var $offer Flux\Fulfillment */
		$fulfillment = new Fulfillment();
		$fulfillment->setSort('name');
		$fulfillment->setSord('asc');
		$fulfillment->setIgnorePagination(true);
		$fulfillments = $fulfillment->queryAll();

		/* @var $data_field Flux\DataField */
		$data_field = new DataField();
		$data_field->setSort('name');
		$data_field->setSord('ASC');
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();

		$this->getContext()->getRequest()->setAttribute("split", $split);
		$this->getContext()->getRequest()->setAttribute("offers", $offers);
		$this->getContext()->getRequest()->setAttribute("fulfillments", $fulfillments);
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		
		return View::SUCCESS;
	}
}

?>