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
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class OfferAssetWizardAction extends BasicAction
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
        if ($this->getContext()->getRequest()->getMethod() == Request::POST) {
            try {
                /* @var $offer_asset Flux\OfferAsset */
                $offer_asset = new OfferAsset();
                $offer_asset->populate($_POST);
                if (isset($_FILES)) {
                	foreach ($_FILES as $file) {
                	    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($_FILES, true));
                	    if (isset($file['tmp_name']) && trim($file['tmp_name']) != '') {
                            $offer_asset->setImageData(base64_encode(file_get_contents($file['tmp_name'])));
                	    }
                	}
                }
                if ($offer_asset->getId() > 0) {
                    $offer_asset->update();
                } else {
                    $offer_asset->insert();
                }

                $this->getContext()->getController()->redirect('/offer/offer?_id=' . $offer_asset->getOfferId() . '#tabs-assets');
            } catch (Exception $e) {
                \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("offer_asset", $offer_asset);
            return View::SUCCESS;
        } else {
            /* @var $offer_asset Flux\OfferAsset */
            $offer_asset = new OfferAsset();
            $offer_asset->populate($_GET);
            if ($offer_asset->getId() > 0) {
                $offer_asset->query();
            }

            $this->getContext()->getRequest()->setAttribute("offer_asset", $offer_asset);
        }
        return View::SUCCESS;
    }
}