<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Client;
use Flux\Flow;
use Flux\Vertical;
use Flux\OfferAsset;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferAssetPreviewModalAction extends BasicAction
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
		/* @var $offer_asset Flux\OfferAsset */
		$offer_asset = new OfferAsset();
		$offer_asset->populate($_GET);
		if (\MongoId::isValid($offer_asset->getId())) {
			$offer_asset->query();
		}

		$this->getContext()->getRequest()->setAttribute("offer_asset", $offer_asset);
		return View::SUCCESS;
	}
}