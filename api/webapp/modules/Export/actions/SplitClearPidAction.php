<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class SplitClearPidAction extends BasicRestAction
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
        return new \Flux\Split();
    }
    
    /**
     * Executes a PUT request
     */
    function executeGet($input_form) {
    	return $this->executePost($input_form);
    }
    
    /**
     * Executes a PUT request
     */
    function executePost($input_form) {
        $ajax_form = new BasicAjaxForm();
        /* @var $split_form \Flux\Split */
        $split_form = new \Flux\Split();
        $split_form->setId($input_form->getId());
        $split_form->query();
        if (\MongoId::isValid($split_form->getId())) {
            $split_form->clearPid();
            $ajax_form->setRowsAffected(1);
            $ajax_form->setRecord($split_form);
        }
        return $ajax_form;
    }
}

?>