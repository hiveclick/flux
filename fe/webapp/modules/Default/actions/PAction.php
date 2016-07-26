<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class PAction extends BasicAction
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
        try {
	    	/* @var $lead \FluxFE\Lead */
    		
    		// Clear the lead if needed
	    	if (isset($_REQUEST['__clear']) && $_REQUEST['__clear'] == '1') {
	    		#\FluxFE\Lead::clear();
	    	}
	
	    	// Get a new instance of the lead (or existing instance) and save it to the db
	    	$lead = \FluxFE\Lead::getInstance();
            
	    	// Add the pixel fire event to this lead
	    	$lead->setValue(\Flux\DataField::DATA_FIELD_EVENT_PIXEL_NAME, 1);
	    	
	    	$lead->update();
	    	
	    	// If we have a payout, then flag the report with a payout
            if (isset($_REQUEST['payout']) && trim($_REQUEST['payout']) != '') {
                /* @var $report_lead \Flux\ReportLead */
                $report_lead = new \Flux\ReportLead();
                $report_lead->setLead($lead->getId());
                $report_lead->setClient($lead->getTracking()->getClient()->getClientId());
                $report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
                $report_lead->setDispositionMessage('Pixel fired at ' . date('m/d/Y g:i a'));
                $report_lead->setRevenue($_REQUEST['payout']);
                if ($lead->getTracking()->getCampaign()->getCampaign()->getPayout() > 0) {
                    $report_lead->setPayout($lead->getTracking()->getCampaign()->getCampaign()->getPayout());
                } else {
                    $report_lead->setPayout($lead->getTracking()->getOffer()->getOffer()->getPayout());
                }
                $report_lead->setReportDate(new \MongoDate(strtotime(date('m/d/Y'))));
                $report_lead->setAccepted(true);
                $report_lead->insert();
	    	}
    	} catch (\Exception $e) {
    	    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
    		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getTraceAsString());
    	}
    	header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
    	
    	echo "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
    	//header('Content-Type: image/gif');
    	//echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
    	return View::SUCCESS;
    }
    
    /**
     * Retrieve the request methods on which this action will process
     * validation and execution.
     */
    public function isSecure ()
    {
    	return false;
    }
}

?>