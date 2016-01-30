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

class DeleteOldDebugScreenshotsAction extends BasicConsoleAction
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
			StringTools::consoleWrite('Finding debug screenshots...', null, StringTools::CONSOLE_COLOR_GREEN, true);
			
			StringTools::consoleWrite(' - Finding records older than 2 weeks', 'finding', StringTools::CONSOLE_COLOR_YELLOW);
			
			$inc = 0;
			$ts = pack('N', strtotime('today - 90 days'));
			$m = substr(md5(gethostname()), 0, 3);
			$pid = pack('n', posix_getpid());
			$trail = substr( pack( 'N', $inc++ ), 1, 3);
			$bin = sprintf("%s%s%s%s", $ts, $m, $pid, $trail);
			
			$id = '';
			for ($i = 0; $i < 12; $i++ )
			{
				$id .= sprintf("%02X", ord($bin[$i]));
			}
			
			
			$lead_split = new \Flux\LeadSplit();
			$criteria = array(
				'last_attempt_time' => array('$lt' => new \MongoDate(strtotime('now - 2 weeks'))),
				'debug_screenshots' => array('$exists' => true)
			);
			$update = array(	
					'$set' => array(
						'debug_screenshots' => array()
					)
			);
				
			$results = $lead_split->updateMultiple($criteria, $update, array('multiple' => true));
			$rows_affected = $results['n'];
			StringTools::consoleWrite(' - Deleted debug screenshots older than 2 weeks', number_format($rows_affected, 0, null, ',') . ' leads', StringTools::CONSOLE_COLOR_GREEN, true);
		} catch (\Exception $e) {
			echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
		}
		return View::NONE;
	}
	
}