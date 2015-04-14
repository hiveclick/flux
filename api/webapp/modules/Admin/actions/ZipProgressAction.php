<?php
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ZipProgressAction extends BasicRestAction
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
     * @return \Flux\Vertical
     */
    function getInputForm() {
        return new \Flux\BackgroundProgress('/tmp/zipcode_update.json');
    }
    
    /**
     * Executes a POST request
     * @return \Mojavi\Form\BasicAjaxForm
     */
    function executeGet($input_form) {
        // Handle POST Requests
        $ajax_form = new \Mojavi\Form\BasicAjaxForm();
        // Download the Zip file
        $ajax_form->setRecord($input_form);
        $ajax_form->setInsertId(1);
        $ajax_form->setRowsAffected(1);
               
        return $ajax_form;
    }
}

?>