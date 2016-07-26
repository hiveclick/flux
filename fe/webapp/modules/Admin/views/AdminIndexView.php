<?php
use Mojavi\View\BasicView;

require_once(MO_MODULE_DIR . DIRECTORY_SEPARATOR . '/Default/views/DefaultIndexView.php');

class AdminIndexView extends DefaultIndexView
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
        // set our template

        // set the title
        $this->setTitle('Flux');

    }

}
