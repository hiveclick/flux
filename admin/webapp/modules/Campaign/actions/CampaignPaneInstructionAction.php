<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Offer;
use Gun\Campaign;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class CampaignPaneInstructionAction extends BasicAction
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
        /* @var $offer Gun\Offer */
        $campaign = new Campaign();
        $campaign->populate($_REQUEST);
        $campaign->query();
        
        $this->getContext()->getRequest()->setAttribute("campaign", $campaign);
        return View::SUCCESS;
    }
}

?>