<?php
/**
 * PingbackKeywordQueue.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   8/18/16 4:47 PM
 */

namespace Flux;

class PingbackKeywordQueue extends Base\PingbackKeywordQueue
{
	private $keyword_array;
	private $link_array;
	private $link;

	/**
	 * @return mixed
	 */
	public function getKeywordArray()
	{
		if (is_null($this->keyword_array)) {
			$this->keyword_array = array();
		}
		return $this->keyword_array;
	}

	/**
	 * @param mixed $keyword_array
	 */
	public function setKeywordArray($keyword_array)
	{
		if (is_array($keyword_array)) {
			$this->keyword_array = $keyword_array;
		} else if (is_string($keyword_array)) {
			if (strpos($keyword_array, "\n") !== false) {
				$this->keyword_array = explode("\n", $keyword_array);
			} else if (strpos($keyword_array, ",") !== false) {
				$this->keyword_array = explode(",", $keyword_array);
			} else {
				$this->keyword_array = array($keyword_array);
			}
		}
		array_walk($this->keyword_array, function(&$value) { $value = trim($value); });
		$this->addModifiedColumn("keyword_array");
	}

	/**
	 * @return mixed
	 */
	public function getLink()
	{
		if (is_null($this->link)) {
			$this->link = "";
		}
		return $this->link;
	}

	/**
	 * @param mixed $link
	 */
	public function setLink($link)
	{
		$this->link = $link;
		$this->addModifiedColumn("link");
	}

	/**
	 * @return mixed
	 */
	public function getLinkArray()
	{
		if (is_null($this->link_array)) {
			$this->link_array = array();
		}
		return $this->link_array;
	}

	/**
	 * @param mixed $link_array
	 */
	public function setLinkArray($link_array)
	{
		if (is_array($link_array)) {
			$this->link_array = $link_array;
		} else if (is_string($link_array)) {
			if (strpos($link_array, "\n") !== false) {
				$this->link_array = explode("\n", $link_array);
			} else if (strpos($link_array, ",") !== false) {
				$this->link_array = explode(",", $link_array);
			} else {
				$this->link_array = array($link_array);
			}
		}
		array_walk($this->link_array, function(&$value) { $value = trim($value); });
		$this->addModifiedColumn("link_array");
	}

	/**
	 * Inserts items into the queue by building them from the link and keyword array
	 * @return integer
	 */
	function insertPingbacksFromArray() {
		$rows_affected = 0;

		if (count($this->getKeywordArray()) == 0) {
			throw new \Exception('No keywords were entered.  Please enter at least one keyword.');
		}
		if (trim($this->getLink()) == "") {
			throw new \Exception('No links were entered.  Please enter at least one link.');
		}
		/* @var $pingback \Flux\Pingback */
		$pingback = new \Flux\Pingback();
		$pingback->setIgnorePagination(true);
		$pingbacks = $pingback->queryAll();

		$next_ping_time_in_seconds = 2;

		foreach ($this->getKeywordArray() as $keyword) {
			foreach ($pingbacks as $pingback) {
				/* @var $pingback_item \Flux\PingbackKeywordQueue */
				$pingback_item = new \Flux\PingbackKeywordQueue();
				$pingback_item->setComment(\Flux\Comment::getRandomComment());
				$pingback_item->setKeyword($keyword);
				$pingback_item->setUrl($this->getLink() . '?x=' . urlencode($keyword));
				$pingback_item->setPingback($pingback->getId());
				$pingback_item->setCreatedAt(new \MongoDate());
				$pingback_item->setNextPingbackTime(strtotime('now + ' . $next_ping_time_in_seconds . ' seconds'));
				$pingback_item->setExpireAt(new \MongoDate(strtotime("now + 1 day")));
				$pingback_item->insert();
				$rows_affected++;
			}
			$next_ping_time_in_seconds += 20;
		}
		return $rows_affected;
	}

	/**
	 * Deletes everything in the queue
	 * @return integer
	 */
	function flushQueue() {
		return parent::deleteMultiple();
	}
}