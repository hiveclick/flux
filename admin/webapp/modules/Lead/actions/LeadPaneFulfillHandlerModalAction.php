<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Lead;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class LeadPaneFulfillHandlerModalAction extends BasicAction
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
        /* @var $lead Flux\Lead */
        $lead = new Lead();
        $lead->populate($_REQUEST);
        $lead->query();

        /* @var $client \Flux\Client */
        $client = new Client();
        $client->setSort('name');
        $client->setSord('ASC');
        $clients = $client->queryAll();
        
        $this->getContext()->getRequest()->setAttribute("lead", $lead);
        $this->getContext()->getRequest()->setAttribute("clients", $clients);
        return View::SUCCESS;
    }
}

?>