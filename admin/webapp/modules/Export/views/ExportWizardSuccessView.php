<?php
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ExportIndexView.php');

class ExportWizardSuccessView extends ExportIndexView
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
        $this->setTitle('Add Export');
    }

}

?>