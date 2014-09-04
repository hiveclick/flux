<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ClientExportMapAction extends BasicRestAction
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
     * @return \Gun\Offer
     */
    function getInputForm() {
        return new \Gun\ClientExport();
    }
    
    /**
     * Executes a PUT request
     */
    function executePut($input_form) {
        /* @var $export_form \Gun\ClientExport */
        $export_form = new \Gun\ClientExport();
        $export_form->setId($input_form->getId());
        $export_form->flushMapping();
        
        return parent::executePut($input_form);
    }
}

?>