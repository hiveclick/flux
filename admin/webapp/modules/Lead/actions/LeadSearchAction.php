<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Lead;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class LeadSearchAction extends BasicAction
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

		/* @var $datafield \Flux\DataField */
		$data_field = new \Flux\DataField();
		$data_field->setSort('name');
		$data_field->setSord('asc');
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();
		
		
		/* @var $vertical \Flux\Vertical */
		$vertical = new \Flux\Vertical();
		$vertical->setSort('name');
		$vertical->setSord('ASC');
		$vertical->setIgnorePagination(true);
		$verticals = $vertical->queryAll();
		
		/* @var $offer \Flux\Offer */
		
		$offer = new \Flux\Offer();
		$offer->setSort('name');
		$offer->setSord('asc');
		$offer->setIgnorePagination(true);
		$offers = $offer->queryAll();
		
		
		/* @var $campaign \Flux\Campaign */
		$campaign = new \Flux\Campaign();
		$campaign->setSort('_id');
		$campaign->setSord('desc');
		$campaign->setItemsPerPage(100);
		$campaigns = $campaign->queryAll();
		

		$this->getContext()->getRequest()->setAttribute("lead", $lead);
		$this->getContext()->getRequest()->setAttribute("offers", $offers);
		$this->getContext()->getRequest()->setAttribute("campaigns", $campaigns);
		$this->getContext()->getRequest()->setAttribute("verticals", $verticals);
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		return View::SUCCESS;
	}
}

?>