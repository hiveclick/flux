<?php
use Mojavi\View\BasicView;

class DefaultIndexView extends BasicView
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
