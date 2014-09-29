<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Vertical;
use Flux\DomainGroup;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class DomainGroupWizardAction extends BasicAction
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
                /* @var $domain_group Flux\DomainGroup */
                $domain_group = new DomainGroup();
                $domain_group->populate($_POST);
                $domain_group->insert();

                $this->getContext()->getController()->redirect('/admin/domain-group-search');
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            $this->getContext()->getRequest()->setAttribute("domain_group", $domain_group);
            return View::SUCCESS;
        } else {
            /* @var $domain_group Flux\DomainGroup */
            $domain_group = new DomainGroup();
            $domain_group->populate($_GET);

            $this->getContext()->getRequest()->setAttribute("domain_group", $domain_group);
        }
        return View::SUCCESS;
    }
}

?>