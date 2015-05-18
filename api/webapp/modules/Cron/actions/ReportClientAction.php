<?php
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
use Mojavi\Action\BasicConsoleAction;
use Mojavi\Util\StringTools;
use Mojavi\View\View;
use Flux\Offer;
use Flux\Campaign;

class ReportClientAction extends BasicConsoleAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute any application/business logic for this action.
     * @return mixed - A string containing the view name associated with this action
     */
    public function execute ()
    {
        try {
            // Compile the number of clicks per offer
            StringTools::consoleWrite('Finding revenue for the month for networks', null, StringTools::CONSOLE_COLOR_GREEN, true);
            $client = new \Flux\Client();
            $client->setIgnorePagination(true);
            $clients = $client->queryAll();
            
            $id = $this->getContext()->getRequest()->getParameter('id', '');
            if (trim($id) != '') {
                $client = new \Flux\Client();
                $client->setId($id);
                $client->query();
                $clients = array(0 => $client);
            }
            
            $start_date = $this->getContext()->getRequest()->getParameter('start_date', '');
            if (trim($start_date) == '') {
                // If it's the first of the month, then close out the previous month, otherwise, just go off the 1st of the month
                if (date('d') == '1') {
                    StringTools::consoleWrite('Closing out last month first', null, StringTools::CONSOLE_COLOR_YELLOW, true);
                    $start_date = date('m/1/Y', strtotime('last month'));   
                } else {
                    $start_date = date('m/1/Y');
                }
            }
            $end_date = $this->getContext()->getRequest()->getParameter('end_date', date('m/d/Y'));
            if (trim($end_date) == '') {
                $end_date = date('m/d/Y');
            }
            
            /* @var $client \Flux\Client */
            foreach ($clients as $client) {
                try {
                    StringTools::consoleWrite('Finding revenue for ' . $client->getName(), null, StringTools::CONSOLE_COLOR_GREEN, true);
                    if ($client->getReportingApiClass() != '') {
                        $reporting_api_class = $client->getReportingApiClassInstance();
                        $reporting_api_class->setStartDate($start_date);
                        $reporting_api_class->setEndDate($end_date);
                        
                        // Sync revenue by network first
                        $reporting_api_class->syncNetworkRevenue($start_date, $end_date);
                        
                        // Sync revenue by network first
                        $reporting_api_class->syncLeadRevenue($start_date, $end_date);
                    } else {
                        throw new \Exception('Reporting API Class not set');
                    }
                } catch (\Exception $e) {
                    StringTools::consoleWrite(' - Error on ' . $client->getName(), $e->getMessage(), StringTools::CONSOLE_COLOR_RED, true);
                }  
            }
        } catch (\Exception $e) {
            echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
        }
        return \Mojavi\View\View::NONE;
    }
    
}