<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Split;
use Gun\Offer;
use Gun\Vertical;
use Gun\DomainGroup;
use Gun\DataField;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class SplitWizardAction extends BasicAction
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
                /* @var $split Gun\Split */
                $split = new Split();
                $split->populate($_POST);
                $split->insert();
                $this->getContext()->getController()->redirect('/export/split?_id=' . $split->getId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("split", $split);
            return View::SUCCESS;
        } else {
            /* @var $split Gun\Split */
            $split = new Split();
            $split->populate($_GET);

            /* @var $offer Gun\Offer */
            $offer = new Offer();
            $offer->setSort('name');
            $offer->setSord('ASC');
            $offer->setIgnorePagination(true);
            $offers = $offer->queryAll();

            /* @var $vertical Gun\Vertical */
            $vertical = new Vertical();
            $vertical->setSort('name');
            $vertical->setSord('ASC');
            $vertical->setIgnorePagination(true);
            $verticals = $vertical->queryAll();

            /* @var $domain_group Gun\DomainGroup */
            $domain_group = new DomainGroup();
            $domain_group->setSort('name');
            $domain_group->setSord('ASC');
            $domain_group->setIgnorePagination(true);
            $domain_groups = $domain_group->queryAll();

            /* @var $data_field Gun\DataField */
            $data_field = new DataField();
            $data_field->setSort('name');
            $data_field->setSord('ASC');
            $data_field->setIgnorePagination(true);
            $data_fields = $data_field->queryAll();

            $this->getContext()->getRequest()->setAttribute("split", $split);
            $this->getContext()->getRequest()->setAttribute("offers", $offers);
            $this->getContext()->getRequest()->setAttribute("verticals", $verticals);
            $this->getContext()->getRequest()->setAttribute("domain_groups", $domain_groups);
            $this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
        }
        return View::SUCCESS;
    }
}

?>