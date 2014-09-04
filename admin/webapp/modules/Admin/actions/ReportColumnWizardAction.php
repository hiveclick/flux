<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\ReportColumn;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ReportColumnWizardAction extends BasicAction
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
                /* @var $report_column Gun\ReportColumn */
                $report_column = new ReportColumn();
                $report_column->populate($_POST);
                $report_column->insert();
            
                $this->getContext()->getController()->redirect('/admin/report-column?_id=' . $report_column->getId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("report_column", $report_column);
            return View::SUCCESS;
        } else {
            /* @var $report_column Gun\ReportColumn */
            $report_column = new ReportColumn();
            $report_column->populate($_GET);
            
            $this->getContext()->getRequest()->setAttribute("report_column", $report_column);
        }
        return View::SUCCESS;
    }
}

?>