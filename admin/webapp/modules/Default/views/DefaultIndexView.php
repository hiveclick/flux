<?php
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
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
        $this->setTitle('Gun');

        $this->setDecoratorTemplate(MO_TEMPLATE_DIR . "/index2.shell.php");

    }

}