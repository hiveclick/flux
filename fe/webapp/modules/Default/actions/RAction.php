<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class RAction extends BasicAction
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
	    		\FluxFE\Lead::clear();
	    	}
	
	    	// Get a new instance of the lead (or existing instance) and save it to the db
	    	$lead = \FluxFE\Lead::getInstance();
			//$lead->save(true);
	
			// Get the redirect url, formatted with placeholders injected
			$redirect_url = $lead->retrieveRedirectUrl();
	
			if (trim($redirect_url) == '') {
				throw new \Exception("No redirect associated with " . $lead->getTracking()->getOffer()->getId() . " and " . $lead->getTracking()->getClient()->getId() . ' from ' . $lead->getTracking()->getCampaign()->getId() . ' (' . $lead->getTracking()->getCampaign()->getOffer()->getId() . '/' . $lead->getTracking()->getCampaign()->getClient()->getId() . ')');	
			}
			
			// Redirect to the formatted url
			$this->getContext()->getController()->redirect($redirect_url);

    	} catch (\Exception $e) {
    		echo "<h1>Bad Request</h1>";
    		echo $e->getMessage();
    		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
    		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getTraceAsString());
    	}
		return View::NONE;
    }

    // -------------------------------------------------------------------------

    /**
     * Retrieve the default view to be executed when a given request is not
     * served by this action.
     * @return mixed - A string containing the view name associated with this action
     */
    public function getDefaultView ()
    {
		return View::NONE;
    }

    // -------------------------------------------------------------------------

    /**
     * Retrieve the request methods on which this action will process
     * validation and execution.
     *
     * @return int - Request::GET - Indicates that this action serves only GET
     *               requests, or...
     *             - Request::POST - Indicates that this action serves only POST
     *               requests, or...
     *             - Request::NONE - Indicates that this action serves no
     *               requests, or...
     */
    public function getRequestMethods ()
    {
        return Request::GET;
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