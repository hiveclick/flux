<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class JAction extends BasicRestAction
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
    	parent::execute();
    	return View::SUCCESS;
    }
    
    /**
	 * Executes a GET request
	 */
    public function executeGet($input_form)
    {
    	// Handle GET Requests
    	/* @var $ajax_form BasicAjaxForm */
    	$ajax_form = new BasicAjaxForm();
    	$lead = \FluxFE\Lead::getInstance();
    	$lead->save(true);
    	$ajax_form->setRecord($lead);
    	return $ajax_form;
    }
    
    /**
	 * Executes a POST request
	 */
    public function executePost($input_form)
    {
    	return $this->executeGet($input_form);
    }
    
    /**
	 * Returns the input form to use for this rest action
	 * @return \FluxFE\Lead
	 */
    public function getInputForm()
    {
    	return \FluxFE\Lead::getInstance();
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