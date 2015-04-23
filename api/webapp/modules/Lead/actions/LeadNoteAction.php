<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class LeadNoteAction extends BasicRestAction
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
     * @return \Flux\Offer
     */
    function getInputForm() {
        return new \Flux\Lead();
    }
    
    /**
     * Handle a POST the same as a GET
     */
    function executePost($input_form) {
    	$ret_val = new BasicAjaxForm();
        $lead = new \Flux\Lead();
        $lead->setId($input_form->getId());
        if ($lead->query() !== false) {
        	$lead->addNote($input_form->getNote());
        	$lead->updateNotes();
        }
    	$ret_val->setRecord($lead);
    	return $ret_val;    
    }
}

?>