<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DefaultIndexView.php');

class JSuccessView extends DefaultIndexView
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
		$this->setDecoratorTemplate(MO_TEMPLATE_DIR . '/json.shell.php');
    }

}
