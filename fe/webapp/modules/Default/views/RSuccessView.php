<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DefaultIndexView.php');

class RSuccessView extends DefaultIndexView
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute any presentation logic and set template attributes.
     *
     * @return void
     */
    public function execute ()
    {
		parent::execute();
    }

}
