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
use Flux\DataField;
use Flux\OfferPagePath;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferPagePanePathOfferModalAction extends BasicAction
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
		
		/* @var $offer_page Flux\OfferPage */
		$offer_page_path = new OfferPagePath();
		
		if (isset($_GET['offer_page_flows']) && is_array($_GET['offer_page_flows'])) {			
			$page_flow_array = array_shift($_GET['offer_page_flows']);
			$offer_page_path->populate($page_flow_array);
		} else {
			$offer_page_path->populate($_GET);
		}
		
		/*@var $offer Flux\Offer */
		$offer = new Offer();
		$offer->setIgnorePagination(true);
		$offer->setSort('name');
		$offer->setSord('ASC');
		$offers = $offer->queryAll();

		$this->getContext()->getRequest()->setAttribute("offer_page_path", $offer_page_path);
		$this->getContext()->getRequest()->setAttribute("offers", $offers);

		return View::SUCCESS;
	}
}