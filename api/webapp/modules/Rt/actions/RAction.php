<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class RAction extends BasicRestAction
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
     * @return \Flux\Lead
     */
    function getInputForm() {
        return new \Flux\Lead();
    }
    
    /**
     * Executes a GET request
     * @var $input_form \Flux\Lead
     */
    function executeGet($input_form) {
        /* @var $ajax_form BasicAjaxForm */
        $ajax_form = new BasicAjaxForm();
        
        // First try to find the lead
        if ($input_form->getId() > 0) {
            $lead = new \Flux\Lead();
            $lead->setId($input_form->getId());
            $lead->query();
            
            if (is_null($lead)) {
                // Lead was not found, so insert a new one
                $insert_id = $input_form->insert();
                $input_form->setId($insert_id);
                $ajax_form->setRecord($input_form);
                $ajax_form->setInsertId($insert_id);
            } else {
                // Lead was found, so update it with the request
                $lead->populate($_REQUEST);
                $input_form->update();
                $ajax_form->setRecord($input_form);
                $ajax_form->setInsertId($input_form->getId());
            }
        } else {
            // No id was passed in, so add a new lead
            $insert_id = $input_form->insert();
            $input_form->setId($insert_id);
            $ajax_form->setInsertId($insert_id);
        }

        return $ajax_form;
    }
    
    /**
     * Executes a POST request
     */
    function executePost($input_form) {
        return $this->executeGet($input_form);
    }
    
    /**
     * Executes a PUT request
     */
    function executePut($input_form) {
        return $this->executeGet($input_form);
    }
}

?>