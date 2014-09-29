<?php
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'LeadIndexView.php');

class LeadSearchSuccessView extends LeadIndexView
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
        $this->setTitle('Leads');

    }

}

?>