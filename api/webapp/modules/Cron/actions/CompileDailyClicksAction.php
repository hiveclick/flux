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

class CompileDailyClicksAction extends BasicConsoleAction
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
            StringTools::consoleWrite('Compiling daily clicks by offer', null, StringTools::CONSOLE_COLOR_GREEN, true);
            StringTools::consoleWrite(' - Finding records', 'finding', StringTools::CONSOLE_COLOR_YELLOW);
            $lead = new \Flux\Lead();
            $criteria = array(
                array(
                    '$match' => array(
                        '_e.t' => array('$gte' => new \MongoDate(strtotime('today midnight')))
                    )
                ),
                array(
                    '$unwind' => '$_e'    	
                ),
                array(
            		'$match' => array(
                        '_e.n' => '_cr'
            		)
                ),
                array(
                    '$group' => array(
                	   '_id' => '$_t._o._id',
                       'count' => array('$sum' => 1)
                    )    	
                )
            );
                        
            $results = $lead->getCollection()->aggregate($criteria);
            if (isset($results['result']) && count($results['result']) > 0) {
                foreach ($results['result'] as $result) {
                    /* @var $offer \Flux\Offer */
                    $offer = new Offer();
                    $offer->setId((int)$result['_id']);
                    if ($offer->query() !== false) {
                        StringTools::consoleWrite(' - ' . $offer->getName() . ' (' . $offer->getId() . ')', number_format($result['count'], 0, null, ',') . ' clicks', StringTools::CONSOLE_COLOR_GREEN, true);
                        $offer->setDailyClicks((int)$result['count']);
                        $offer->update();
                    } else {
                	    StringTools::consoleWrite(' - No Offer (' . $result['_id'] . ')', number_format($result['count'], 0, null, ',') . ' clicks', StringTools::CONSOLE_COLOR_GREEN, true);
                	}
                }
            } else {
                StringTools::consoleWrite(' - No conversions found for today', null, StringTools::CONSOLE_COLOR_RED, true);
            }
            

            // Compile the number of conversions per offer
            StringTools::consoleWrite('Compiling daily conversions by offer', null, StringTools::CONSOLE_COLOR_GREEN, true);
            StringTools::consoleWrite(' - Finding records', 'finding', StringTools::CONSOLE_COLOR_YELLOW);
            $lead = new \Flux\Lead();
            $criteria = array(
            		array(
            				'$match' => array(
            						'_e.t' => array('$gte' => new \MongoDate(strtotime('today midnight')))
            				)
            		),
            		array(
            				'$unwind' => '$_e'
            		),
            		array(
            				'$match' => array(
            						'_e.n' => 'conv'
            				)
            		),
            		array(
            				'$group' => array(
            						'_id' => '$_t._o._id',
            						'count' => array('$sum' => 1)
            				)
            		)
            );
            
            $results = $lead->getCollection()->aggregate($criteria);
            if (isset($results['result']) && count($results['result']) > 0) {
                foreach ($results['result'] as $result) {
                	/* @var $offer \Flux\Offer */
                	$offer = new Offer();
                	$offer->setId((int)$result['_id']);
                	if ($offer->query() !== false) {
                	    StringTools::consoleWrite(' - ' . $offer->getName(), number_format($result['count'], 0, null, ',') . ' conversions', StringTools::CONSOLE_COLOR_GREEN, true);
                		$offer->setDailyConversions((int)$result['count']);
                		$offer->update();
                	} else {
                	    StringTools::consoleWrite(' - No Offer (' . $result['_id'] . ')', number_format($result['count'], 0, null, ',') . ' conversions', StringTools::CONSOLE_COLOR_GREEN, true);
                	}
                }
            } else {
                StringTools::consoleWrite(' - No conversions found for today', null, StringTools::CONSOLE_COLOR_RED, true);
            }
            
            
            // Compile the number of clicks per campaign
            StringTools::consoleWrite('Compiling daily clicks by campaign', null, StringTools::CONSOLE_COLOR_GREEN, true);
            StringTools::consoleWrite(' - Finding records', 'finding', StringTools::CONSOLE_COLOR_YELLOW);
            $lead = new \Flux\Lead();
            $criteria = array(
            		array(
            				'$match' => array(
            						'_e.t' => array('$gte' => new \MongoDate(strtotime('today midnight')))
            				)
            		),
            		array(
            				'$unwind' => '$_e'
            		),
            		array(
            				'$match' => array(
            						'_e.n' => '_cr'
            				)
            		),
            		array(
            				'$group' => array(
            						'_id' => '$_t._ck._id',
            						'count' => array('$sum' => 1)
            				)
            		)
            );
            
            $results = $lead->getCollection()->aggregate($criteria);
            
            if (isset($results['result']) && count($results['result']) > 0) {
                foreach ($results['result'] as $result) {
                	/* @var $campaign \Flux\Campaign */
                	$campaign = new Campaign();
                	$campaign->setId($result['_id']);
                	if ($campaign->query() !== false) {
                	    StringTools::consoleWrite(' - ' . $campaign->getId(), number_format($result['count'], 0, null, ',') . ' clicks', StringTools::CONSOLE_COLOR_GREEN, true);
                		$campaign->setDailyClicks((int)$result['count']);
                		$campaign->update();
                	} else {
                	    StringTools::consoleWrite(' - No Campaign (' . $result['_id'] . ')', number_format($result['count'], 0, null, ',') . ' clicks', StringTools::CONSOLE_COLOR_GREEN, true);
                	}
                }
            } else {
                StringTools::consoleWrite(' - No clicks found for today', null, StringTools::CONSOLE_COLOR_RED, true);
            }
            
            // Compile the number of conversions per campaign
            StringTools::consoleWrite('Compiling daily conversions by campaign', null, StringTools::CONSOLE_COLOR_GREEN, true);
            StringTools::consoleWrite(' - Finding records', 'finding', StringTools::CONSOLE_COLOR_YELLOW);
            $lead = new \Flux\Lead();
            $criteria = array(
            		array(
            				'$match' => array(
            						'_e.t' => array('$gte' => new \MongoDate(strtotime('today midnight')))
            				)
            		),
            		array(
            				'$unwind' => '$_e'
            		),
            		array(
            				'$match' => array(
            						'_e.n' => 'conv'
            				)
            		),
            		array(
            				'$group' => array(
            						'_id' => '$_t._ck._id',
            						'count' => array('$sum' => 1)
            				)
            		)
            );
            
            $results = $lead->getCollection()->aggregate($criteria);      
            if (isset($results['result']) && count($results['result']) > 0) {
                foreach ($results['result'] as $result) {
                	/* @var $campaign \Flux\Campaign */
                	$campaign = new Campaign();
                	$campaign->setId($result['_id']);
                	if ($campaign->query() !== false) {
                		StringTools::consoleWrite(' - ' . $campaign->getId(), number_format($result['count'], 0, null, ',') . ' conversions', StringTools::CONSOLE_COLOR_GREEN, true);
                		$campaign->setDailyConversions((int)$result['count']);
                		$campaign->update();
                	} else {
                	    StringTools::consoleWrite(' - No Campaign (' . $result['_id'] . ')', number_format($result['count'], 0, null, ',') . ' conversions', StringTools::CONSOLE_COLOR_GREEN, true);
                	}
                }
            } else {
                StringTools::consoleWrite(' - No clicks found for today', null, StringTools::CONSOLE_COLOR_RED, true);
            }
            
            // Also clear out old numbers
        } catch (\Exception $e) {
            echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
        }
        return View::NONE;
    }
    
}