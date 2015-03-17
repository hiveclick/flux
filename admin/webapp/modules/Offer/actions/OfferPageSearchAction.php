<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Client;
use Flux\Flow;
use Flux\Vertical;
use Mojavi\Logging\LoggerManager;
use Flux\OfferPage;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferPageSearchAction extends BasicAction
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
		$offer = new Offer();
		$offer->populate($_REQUEST);
		$offer->query();

		/* @var $offer_page Flux\OfferPage */
		$offer_page = new \Flux\OfferPage();
		$offer_page->setOfferIdArray(array($offer->getId()));
		$offer_page->setIgnorePagination(true);
		$offer_page->setSort('priority');
		$offer_page->setSord('ASC');
		$offer_pages = $offer_page->queryAll();

		$this->getContext()->getRequest()->setAttribute("offer", $offer);
		$this->getContext()->getRequest()->setAttribute("offer_pages", $offer_pages);
		
		return View::SUCCESS;
	}
}