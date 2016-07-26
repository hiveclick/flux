<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'IndexBlankView.php');

class PSuccessView extends IndexBlankView
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
