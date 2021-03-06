<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferWizardAction extends BasicAction
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
		/* @var $offer Flux\Offer */
		$offer = new \Flux\Offer();
		$offer->populate($_GET);

		/* @var $client Flux\Client */
		$client = new \Flux\Client();
		$client->setSort('name');
		$client->setSord('ASC');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();

		/* @var $flow Flux\Flow */
		$flow = new \Flux\Flow();
		$flow->setSort('name');
		$flow->setIgnorePagination(true);
		$flows = $flow->queryAll();

		/* @var $vertical Flux\Vertical */
		$vertical = new \Flux\Vertical();
		$vertical->setSort('name');
		$vertical->setSord('ASC');
		$vertical->setIgnorePagination(true);
		$verticals = $vertical->queryAll();
		
		/* @var $split Flux\Split */
		$split = new \Flux\Split();
		$split->setSort('name');
		$split->setSord('ASC');
		$split->setSplitType(\Flux\Split::SPLIT_TYPE_HOST_POST);
		$split->setIgnorePagination(true);
		$splits = $split->queryAll();

		$this->getContext()->getRequest()->setAttribute("offer", $offer);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		$this->getContext()->getRequest()->setAttribute("flows", $flows);
		$this->getContext()->getRequest()->setAttribute("verticals", $verticals);
		$this->getContext()->getRequest()->setAttribute("splits", $splits);
		
		return View::SUCCESS;
	}
}