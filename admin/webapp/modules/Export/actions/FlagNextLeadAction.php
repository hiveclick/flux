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
class FlagNextLeadAction extends BasicAction
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
	    if ($this->getContext()->getRequest()->getMethod() == \Mojavi\Request\Request::GET) {
    		/* @var $split_queue Flux\SplitQueue */
    		$split_queue = new \Flux\SplitQueue();
    		$split_queue->populate($_GET);
    		$split_queue->query();
    		
    		$this->getContext()->getRequest()->setAttribute("split_queue", $split_queue);
    		return View::INPUT;
	    } else if ($this->getContext()->getRequest()->getMethod() == \Mojavi\Request\Request::POST) {
	        /* @var $split_queue Flux\SplitQueue */
	        $split_queue = new \Flux\SplitQueue();
	        $split_queue->populate($_POST);
	        $split_queue->query();
	        
	        /* @var $split_queue_attempt \Flux\SplitQueueAttempt */
	        $split_queue_attempt = new \Flux\SplitQueueAttempt();
	        $split_queue_attempt->setAttemptTime(new \MongoDate());
	        $split_queue_attempt->setFulfillment(array('fulfillment_id' => 0, 'fulfillment_name' => 'uBot Script'));
	        $split_queue_attempt->setResponse($_POST['response']);
	        if (isset($_POST['error_message']) && trim($_POST['error_message']) != '') {
	            $split_queue_attempt->setErrorMessage($_POST['error_message']);
	            $split_queue_attempt->setIsError(true);
	            $split_queue->setErrorMessage($_POST['error_message']);
	        }
	        if (isset($_POST['disposition']) && trim($_POST['disposition']) != '') {
	            $split_queue->setDisposition($_POST['disposition']);
	        }
	        if (isset($_FILES['screenshot'])) {
	            if (file_exists($_FILES['screenshot']['tmp_name'])) {
                    $split_queue_attempt->setScreenshot(base64_encode(file_get_contents($_FILES['screenshot']['tmp_name'])));
	            }
	        }
	        $split_queue->addAttempt($split_queue_attempt);
	        $split_queue->setLastAttemptTime(new \MongoDate());
	        $split_queue->update();	   

	        if ($split_queue->getDisposition() == \Flux\SplitQueue::DISPOSITION_FULFILLED) {
	            // Add a fulfilled event to the lead
    	        /* @var $lead \Flux\Lead */
    	        $lead = $split_queue->getLead()->getLead();
    	        $lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 1);
    	        $lead->update();
    	        
    	        // Add/Update the lead reporting
    	        /* @var $report_lead \Flux\ReportLead */
    	        $report_lead = new \Flux\ReportLead();
    	        $report_lead->setLead($lead->getId());
    	        $report_lead->setClient($lead->getTracking()->getClient()->getClientId());
    	        $report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
    	        $report_lead->setRevenue($split_queue->getSplit()->getSplit()->getFulfillment()->getFulfillment()->getBounty());
    	        if ($lead->getTracking()->getCampaign()->getCampaign()->getPayout() > 0) {
                    $report_lead->setPayout($lead->getTracking()->getCampaign()->getCampaign()->getPayout());
    	        } else {
    	            $report_lead->setPayout($lead->getTracking()->getOffer()->getOffer()->getPayout());
    	        }
    	        $report_lead->setReportDate(new \MongoDate());
    	        $report_lead->setAccepted(true);
    	        $report_lead->insert();
	        } else {
	            /* @var $report_lead \Flux\ReportLead */
	            $report_lead = new \Flux\ReportLead();
	            $lead = $split_queue->getLead()->getLead();
	            $report_lead->setLead($lead->getId());
	            $report_lead->setClient($lead->getTracking()->getClient()->getClientId());
	            $report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED);
	            $report_lead->setRevenue(0.00);
	            $report_lead->setPayout(0.00);
	            $report_lead->setReportDate(new \MongoDate());
	            $report_lead->insert();
	        }
	        
	        $this->getContext()->getRequest()->setAttribute("split_queue", $split_queue);
	        return View::SUCCESS;
	    }
	}
}

?>