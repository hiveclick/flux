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

class PingomaticAction extends BasicConsoleAction
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
			StringTools::consoleWrite('Getting Pingomatic URLs', null, StringTools::CONSOLE_COLOR_GREEN, true);
			$ubot = new \Flux\Ubot();
			$ubot->setIgnorePagination(true);
			$ubots = $ubot->queryAll();

			$pings = array();
			/* @var $ubot \Flux\Ubot */
			foreach ($ubots as $ubot) {
				foreach ($ubot->getUrls() as $url) {
					$pings[] = $url;
				}
			}

			shuffle($pings);

			StringTools::consoleWrite(' - Processing Pingomatic URLs', null, StringTools::CONSOLE_COLOR_GREEN, true);
			$counter = 1;
			foreach ($pings as $ping) {
				StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Pingomatic URL', $ping, StringTools::CONSOLE_COLOR_GREEN, true);
				$result = $this->sendPing($ping, '');
				if (strpos($result, 'Ping sent') !== false) {
					StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Pingomatic URL', 'Success', StringTools::CONSOLE_COLOR_GREEN, true);
					$throttle_time = 3;
				} else if (strpos($result, 'Slow down cowboy') !== false) {
					StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Pingomatic URL', 'Throttling', StringTools::CONSOLE_COLOR_CYAN, true);
					$throttle_time = rand(5,8);
				} else if (strpos($result, 'No blog URL') !== false) {
					StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Pingomatic URL', 'Retrying with /', StringTools::CONSOLE_COLOR_PURPLE, true);
					$result = $this->sendPing(($ping . '/'), '');
					if (strpos($result, 'Ping sent') !== false) {
						StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Pingomatic URL', 'Success', StringTools::CONSOLE_COLOR_GREEN, true);
						$throttle_time = 3;
					} else if (strpos($result, 'Slow down cowboy') !== false) {
						StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Pingomatic URL', 'Throttling', StringTools::CONSOLE_COLOR_CYAN, true);
						$throttle_time = rand(15,22);
					} else {
						StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Pingomatic URL', 'Error', StringTools::CONSOLE_COLOR_RED, true);
						echo $result . "\n";
						$throttle_time = 3;
					}
				} else {
					StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Pingomatic URL', 'Error', StringTools::CONSOLE_COLOR_RED, true);
					echo $result . "\n";
					$throttle_time = 3;
				}

				for ($i=$throttle_time;$i>1;$i--) {
					StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Throttling pings', 'Waiting for ' . $i . ' minutes', StringTools::CONSOLE_COLOR_CYAN, true);
					sleep(60);
				}
				for ($i=rand(10,60);$i>0;$i--) {
					StringTools::consoleWrite('   [' . $counter . '/' . count($pings) . '] Throttling pings', 'Waiting for ' . $i . ' seconds', StringTools::CONSOLE_COLOR_CYAN);
					sleep(1);
				}
				$counter++;
			}
			
			// Also clear out old numbers
		} catch (\Exception $e) {
			echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
		}
		return View::NONE;
	}

	/**
	 * Sends the ping to pingomatic
	 * @param $blog_title string
	 * @param $blog_url string
	 * @return string
	 */
	function sendPing($blog_url, $blog_title = '') {
		try {
			$pingomatic_url = 'http://pingomatic.com/ping/?title=%s&blogurl=%s&rssurl=&chk_weblogscom=on&chk_blogs=on&chk_feedburner=on&chk_newsgator=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_weblogalot=on&chk_newsisfree=on&chk_topicexchange=on&chk_google=on&chk_tailrank=on&chk_skygrid=on&chk_collecta=on&chk_superfeedr=on';
			$url = sprintf($pingomatic_url, $blog_title, $blog_url);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/601.7.8 (KHTML, like Gecko) Version/9.1.3 Safari/601.7.8');
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($ch);

			if (strpos($result, 'Ping sent.') !== false) {
				return 'Ping sent';
			} else if (strpos($result, 'Slow down cowboy!') !== false) {
				return 'Slow down cowboy';
			} else if (strpos($result, 'No blog URL?') !== false) {
				return 'No blog URL';
			} else {
				return $result;
			}
		} catch (\Exception $e) {
			echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
		}
	}
	
}