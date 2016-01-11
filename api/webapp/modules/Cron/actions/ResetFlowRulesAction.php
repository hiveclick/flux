<?php
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
use Mojavi\Action\BasicConsoleAction;
use Mojavi\Util\StringTools;
use Mojavi\View\View;
use Flux\Offer;
use Flux\Campaign;

class ResetFlowRulesAction extends BasicConsoleAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		try {
			// Compile the number of clicks per offer
			StringTools::consoleWrite('Resetting flow rules', null, StringTools::CONSOLE_COLOR_GREEN, true);
			$campaign = new \Flux\Campaign();
			$campaign->setIgnorePagination(true);
			$campaigns = $campaign->queryAll();
			/* @var $campaign \Flux\Campaign */
			foreach ($campaigns as $campaign) {
				if (count($campaign->getFlowRules()) > 0) {
					StringTools::consoleWrite(' - ' . $campaign->getDescription(), null, StringTools::CONSOLE_COLOR_GREEN, true);
					/* @var $flow_rule \Flux\Link\FlowRule */
					foreach ($campaign->getFlowRules() as $key => $flow_rule) {
						StringTools::consoleWrite('   - ' . $flow_rule->getName(), 'Updated #' . $key, StringTools::CONSOLE_COLOR_GREEN, true);
						$campaign->update(array('_id' => $campaign->getId()), array('$set' => array('flow_rules.' . $key . '.daily_click_count' => 0)));
					}
				} else {
					StringTools::consoleWrite(' - ' . $campaign->getDescription(), 'No Rules', StringTools::CONSOLE_COLOR_YELLOW, true);
				}
			}			
			// Also clear out old numbers
		} catch (\Exception $e) {
			echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
		}
		return View::NONE;
	}
	
}