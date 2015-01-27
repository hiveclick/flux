<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Campaign;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferPaneInstructionAction extends BasicAction
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
		
		$this->getContext()->getRequest()->setAttribute("offer", $offer);
		
		/* @var $campaign Flux\Campaign */
		$campaign = new Campaign();
		$campaign->setOfferId($offer->getId());
		$campaigns = $campaign->queryAllByOffer();
		$this->getContext()->getRequest()->setAttribute("campaigns", $campaigns);
		 
		return View::SUCCESS;
	}
}

?>