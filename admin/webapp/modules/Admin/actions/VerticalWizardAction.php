<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Vertical;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class VerticalWizardAction extends BasicAction
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
            try {
                /* @var $vertical Gun\Vertical */
                $vertical = new Vertical();
                $vertical->populate($_POST);
                $vertical->insert();

                $this->getContext()->getController()->redirect('/admin/vertical-search');
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("vertical", $vertical);
            return View::SUCCESS;
        } else {
            /* @var $vertical Gun\Vertical */
            $vertical = new Vertical();
            $vertical->populate($_GET);

            $this->getContext()->getRequest()->setAttribute("vertical", $vertical);
        }
        return View::SUCCESS;
    }
}

?>