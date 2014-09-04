<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\DataField;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class DataFieldAction extends BasicAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute any application/business logic for this action.
     *
     * @return mixed - A string containing the view name associated with this action
     */
    public function execute ()
    {
        if ($this->getContext()->getRequest()->getMethod() == Request::POST) {
            /* @var $datafield Gun\DataField */
            $datafield = new DataField();
            $datafield->populate($_POST);
            $datafield->update();
            
            $this->getContext()->getRequest()->setAttribute("datafield", $datafield);
        } else {
            /* @var $datafield Gun\DataField */
            $datafield = new DataField();
            $datafield->populate($_GET);
            $datafield->query();
            
            $this->getContext()->getRequest()->setAttribute("datafield", $datafield);
        }
        return View::SUCCESS;
    }
}

?>