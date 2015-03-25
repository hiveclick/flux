<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\View\View;
use Flux\Pixel;
use Mojavi\Logging\LoggerManager;
use Flux\LeadPage;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class PxlAction extends BasicRestAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute any application/business logic for this action.
     * @return mixed - A string containing the view name associated with this action
     */
    public function execute ()
    {
        
        /* @var $pixel \Flux\Pixel */
        $pixel = new Pixel();
        $pixel->populate($_REQUEST);
        if ($pixel->getPage() == '') { $pixel->setPage('index.php'); }
        #\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($pixel, true));
        // Track the page name and find the offer associated with it
        if ($pixel->getOffer()->getOfferId() > 0) {
            $offer_page = new \Flux\OfferPage();
            $offer_page->setOffer($pixel->getOffer()->getOfferId());
            $offer_page->setPageName($pixel->getPage());
            if (($page_found = $offer_page->queryByPageName()) === false) {
            	// we couldn't find the page, so add it
                $offer_page = new \Flux\OfferPage();
            	$offer_page->setOffer($pixel->getOffer()->getOfferId());
            	$offer_page->setPageName($pixel->getPage());
            	$offer_page->setPreviewUrl($pixel->getHref());
            	$file_path = $pixel->getOffer()->getOffer()->getDocrootDir() . ($pixel->getFolder() != '' ? '/' . $pixel->getFolder() : ''). ($pixel->getPage() != '' ? '/' . $pixel->getPage() : '');
            	
            	$offer_page->setFilePath($file_path);
            	$offer_page->setPriority(100);
            	$offer_page->setName($pixel->getPage());
            	$offer_page->insert();
            } else {
                \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . 'Page ' . $pixel->getPage() . ' already exists on offer ' . $pixel->getOffer()->getOfferName());
            }
            
            // Increment the click count on the offer page
            $offer_page->update(array(), array('$inc' => array('click_today' => 1)));
        
            // Add the offer page visit to the lead and track the time spent on the page
            if (\MongoId::isValid($pixel->getLead()->getLeadId())) {
                $lead_page = new LeadPage();
                $lead_page->setLead($pixel->getLead()->getLeadId());
                $lead_page->setOffer($pixel->getOffer()->getOfferId());
                $lead_page->setOfferPage($offer_page->getId());
                $lead_page->setHref($pixel->getHref());
                $lead_page->setPage($pixel->getPage());
                $lead_page->setDomain($pixel->getDomain());
                $lead_page->setFolder($pixel->getFolder());
                $lead_page->setCookies(array($pixel->getCookie()));
                
                $criteria_array = array('lead.lead_id' => (string)$lead_page->getLead()->getLeadId(), 'offer_page.offer_page_id' => $lead_page->getOfferPage()->getOfferPageId());
                \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($criteria_array, true));
                $insert_array = $lead_page->toArray();
                $upsert_array = array('exit_time' => new \MongoDate());
                $push_array = array('cookies' => array('t' => new \MongoDate(), 'data' => $pixel->getCookie()));
                if (isset($insert_array['cookies'])) { unset($insert_array['cookies']); }
                if (isset($insert_array['exit_time'])) { unset($insert_array['exit_time']); }
                if (isset($insert_array['load_count'])) { unset($insert_array['load_count']); }
                if (isset($insert_array['_id'])) { unset($insert_array['_id']); }
                
                $update_array = array('$setOnInsert' => $insert_array, '$set' => $upsert_array, '$push' => $push_array, '$inc' => array('load_count' => 1));                
                $lead_page->updateMultiple($criteria_array, $update_array);                                
            } else {
                \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . 'No lead found from pixel using id ' . $pixel->getLead()->getLeadId());
            }
        } else {
        	\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . 'No offer found (' . $pixel->getOffer()->getId() . ') from pixel using domain ' . $pixel->getDomain() . '/' . $pixel->getFolder());
        }
        
        // Finally return a 1x1 pixel gif for tracking
        $img = imagecreatefromstring(base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw=='));
        imagejpeg($img);
        return View::NONE;
    }
}

?>