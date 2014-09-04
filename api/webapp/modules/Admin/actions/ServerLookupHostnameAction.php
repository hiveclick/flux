<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ServerLookupHostnameAction extends BasicRestAction
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
     * @return \Gun\Server
     */
    function getInputForm() {
        return new \Gun\Server();
    }

    /**
     * Looks up the server hostname
     * @return \Mojavi\Form\BasicAjaxForm
     */
    function executeGet($input_form) {
        // Handle GET Requests
        $ajax_form = new BasicAjaxForm();
        $input_form->lookupHostname();
        $ajax_form->setRecord($input_form);
        return $ajax_form;
    }
}

?>