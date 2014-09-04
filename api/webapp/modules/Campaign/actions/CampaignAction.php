<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class CampaignAction extends BasicRestAction
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
     * @return \Gun\Campaign
     */
    function getInputForm() {
        return new \Gun\Campaign();
    }
    
    /**
     * Executes a GET request
     */
    function executeGet($input_form) {
        // Handle GET Requests
        /* @var $ajax_form BasicAjaxForm */
        $ajax_form = new BasicAjaxForm();
                
        if ($input_form->getId() instanceof \MongoId) {
            $input_form->query();
            $ajax_form->setRecord($input_form);
        } else {
            $entries = $input_form->queryAll();
            $ajax_form->setEntries($entries);
            $ajax_form->setTotal($input_form->getTotal());
            $ajax_form->setPage($input_form->getPage());
            $ajax_form->setItemsPerPage($input_form->getItemsPerPage());
        }
        return $ajax_form;
    }
}

?>