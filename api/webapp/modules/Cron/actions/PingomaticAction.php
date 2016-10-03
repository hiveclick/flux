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
			StringTools::consoleWrite('Finding Pingomatic Script', null, StringTools::CONSOLE_COLOR_GREEN, true);
			$pingbot = new \Flux\Ubot();
			$pingbot = $pingbot->query(array('type' => \Flux\Ubot::TYPE_PINGOMATIC), false);
			if (is_object($pingbot) && \MongoId::isValid($pingbot->getId())) {
				StringTools::consoleWrite('  - Pingomatic Script', $pingbot->getName(), StringTools::CONSOLE_COLOR_GREEN, true);
			} else {
				throw new \Exception('No Pingomatic Script could be found in the list of uBot Scripts');
			}

			StringTools::consoleWrite('Finding Random uBot Url', null, StringTools::CONSOLE_COLOR_GREEN, true);
			$ubot = new \Flux\Ubot();
			$ubot = $ubot->findAndModify(array(
					'type' => \Flux\Ubot::TYPE_COMMENT,
					'last_ping_time' => array('$lt' => new \MongoDate(strtotime('now - 1 hour')))
				),
				array(
					'$set' => array(
						'last_ping_time' => new \MongoDate()
					)
				),
				null,
				array(
					'new' => true,
					'sort' => array('last_ping_time' => 1)
				)
			);

			if (is_object($ubot) && \MongoId::isValid($ubot->getId())) {
				StringTools::consoleWrite('  - uBot Script', $ubot->getName(), StringTools::CONSOLE_COLOR_GREEN, true);
				$urls = $ubot->getUrls();
				if (!is_array($urls)) {
					throw new \Exception('Urls not present on uBot script ' . $ubot->getName());
				}
				shuffle($urls);
				$url = array_shift($urls);

				if (trim($url) == '') {
					throw new \Exception('Url is blank on uBot script ' . $ubot->getName());
				}
				StringTools::consoleWrite('  - Processing Pingomatic URL', $url, StringTools::CONSOLE_COLOR_GREEN, true);
				$ubot_queue = new \Flux\UbotQueue();
				$ubot_queue->setUrl($url);
				$ubot_queue->setKeyword('Pingomatic');
				$ubot_queue->setName('Pingomatic');
				$ubot_queue->setEmail('Pingomatic');
				$ubot_queue->setUbot($pingbot->getId());
				$ubot_queue->setLink($url);
				$ubot_queue->insert();
				StringTools::consoleWrite('Pingomatic Saved!', null, StringTools::CONSOLE_COLOR_GREEN, true);
			} else {
				throw new \Exception('No uBot scripts could be found, please wait 1 hour');
			}
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