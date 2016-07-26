<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class FlushCampaignCacheAction extends BasicRestAction
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
        return parent::execute();
    }

    /**
     * Returns the input form to use for this rest action
     * @return \Flux\DataField
     */
    function getInputForm() {
        return new \FluxFE\Campaign();
    }
    
    /**
     * Looks up the server hostname
     * @param $input_form \FluxFE\Offer
     * @return \Mojavi\Form\BasicAjaxForm
     */
    function executeGet($input_form) {
    	return $this->executePost($input_form);
    }
    
    /**
     * Looks up the server hostname
     * @param $input_form \FluxFE\Offer 
     * @return \Mojavi\Form\BasicAjaxForm
     */
    function executePost($input_form) {
    	// Handle GET Requests
    	$ajax_form = new BasicAjaxForm();
    	
    	$hashes = array();
    	$hashes = $input_form->getCacheFilenames();
    		
	    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Clearing cache: " . var_export($hashes, true));
    	
    	$ajax_form->setRecord($input_form);
    	return $ajax_form;
    }
}
