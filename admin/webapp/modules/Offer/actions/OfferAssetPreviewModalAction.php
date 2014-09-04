<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Offer;
use Gun\Client;
use Gun\Flow;
use Gun\Vertical;
use Gun\OfferAsset;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class OfferAssetPreviewModalAction extends BasicAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute any application/business logic for this action.
     *
     * @return mixed - A string containing the view name associated with this action
     */
    public function execute ()
    {
        /* @var $offer_asset Gun\OfferAsset */
        $offer_asset = new OfferAsset();
        $offer_asset->populate($_GET);
        if ($offer_asset->getId() > 0) {
            $offer_asset->query();
        }

        $this->getContext()->getRequest()->setAttribute("offer_asset", $offer_asset);
        return View::SUCCESS;
    }
}