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

class DeleteOldClicksAction extends BasicConsoleAction
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
			StringTools::consoleWrite('Finding clicks older than 3 months', null, StringTools::CONSOLE_COLOR_GREEN, true);
			
			StringTools::consoleWrite(' - Finding records with 0 events', 'finding', StringTools::CONSOLE_COLOR_YELLOW);
			
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
			
			
			$lead = new \Flux\Lead();
			$criteria = array(
					'_e' => array('$size' => 0),
					'_id' => array('$lte' => new \MongoId($id)),
			);
				
			$results = $lead->getCollection()->remove($criteria);
			$rows_affected = $results['n'];
			StringTools::consoleWrite(' - Deleted Rows with 0 events', number_format($rows_affected, 0, null, ',') . ' leads', StringTools::CONSOLE_COLOR_GREEN, true);
			
			StringTools::consoleWrite(' - Finding records with 1 event', 'finding', StringTools::CONSOLE_COLOR_YELLOW);
			$lead = new \Flux\Lead();
			$criteria = array(
				'_e' => array('$size' => 1),
				'_e.t' => array('$lte' => new \MongoDate(strtotime('today - 90 days'))),
				'_e.data_field.name' => array('$nin' => array(\Flux\DataField::DATA_FIELD_EVENT_PIXEL_NAME, \Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME, \Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME))
			);
									
			$results = $lead->getCollection()->remove($criteria);
			$rows_affected = $results['n'];
			StringTools::consoleWrite(' - Deleted Rows with 1 event', number_format($rows_affected, 0, null, ',') . ' leads', StringTools::CONSOLE_COLOR_GREEN, true);
			// Also clear out old numbers
		} catch (\Exception $e) {
			echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
		}
		return View::NONE;
	}
	
}