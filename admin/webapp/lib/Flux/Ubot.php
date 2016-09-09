<?php
/**
 * Ubot.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   9/1/16 10:12 AM
 */

namespace Flux;


class Ubot extends Base\Ubot
{
	/**
	 * Refreshes the url list from the RSS feed
	 * @return void
	 */
	function refreshFromRss() {
		$base_url = '';
		$str = file_get_contents($this->getRssUrl());
		$str = preg_replace('/&(?!;{6})/', '&amp;', $str);
		$xml = simplexml_load_string($str);
		$urls = array();
		if (isset($xml->channel)) {
			// entry is used for RSS 2.0 feeds
			foreach ($xml->channel->item as $item) {
				$item_link = $item->link;
				$urls[] = $item_link;
			}
			$this->setUrls($urls);
		} else {
			if (isset($xml->link)) {
				$base_url = $xml->link->attributes()->href;
			}

			// entry is used for ATOM feeds
			foreach ($xml->entry as $item) {
				$item_link = $item->link->attributes()->href;
				$urls[] = $base_url . $item_link;
			}
			$this->setUrls($urls);
		}
		if (\MongoId::isValid($this->getId())) {
			$this->updateMultiple(
				array('_id' => $this->getId()),
				array(
					'$set' => array(
						'urls' => $urls,
						'last_rss_update_at' => new \MongoDate()
					)
				)
			);
		}
		return true;
	}
}