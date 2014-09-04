<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Vertical;
use Gun\DomainGroup;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class DomainGroupSearchAction extends BasicAction
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
        /* @var $domain_group Gun\DomainGroup */
        $domain_group = new DomainGroup();
        $domain_group->populate($_REQUEST);

        $this->getContext()->getRequest()->setAttribute("domain_group", $domain_group);
        return View::SUCCESS;
    }
}

?>