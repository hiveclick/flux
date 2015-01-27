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
use Flux\OfferPageFlow;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferPagePaneFlowSetterModalAction extends BasicAction
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
		$offer_page_flow = new OfferPageFlow();
		
		if (isset($_GET['offer_page_flows']) && is_array($_GET['offer_page_flows'])) {			
			$page_flow_array = array_shift($_GET['offer_page_flows']);
			$offer_page_flow->populate($page_flow_array);
		} else {
			$offer_page_flow->populate($_GET);
		}

		$this->getContext()->getRequest()->setAttribute("offer_page_flow", $offer_page_flow);

		return View::SUCCESS;
	}
}