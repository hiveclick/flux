<?php
/**
 * UbotQueue.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   9/1/16 12:26 PM
 */

namespace Flux;


class UbotQueue extends Base\UbotQueue
{
	private $keyword_array;

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
	 * Returns the formatted comment
	 * @return string
	 */
	function getFormattedComment() {
		$comment = $this->getComment();
		$comment = str_replace("[link]", $this->getLink(), $comment);
		$comment = str_replace("[keyword]", $this->getKeyword(), $comment);
		$comment = str_replace("[anchor]", '<a href="' . $this->getLink() . '">' . $this->getKeyword() . '</a>', $comment);
		return $comment;
	}

	/**
	 * Returns the formatted comment
	 * @return string
	 */
	function getFormattedLink() {
		return '<a href="' . $this->getLink() . '">' . $this->getKeyword() . '</a>';
	}

	/**
	 * Inserts items into the queue by building them from the link and keyword array
	 * @return integer
	 */
	function insertCommentsFromArray() {
		$rows_affected = 0;

		if (count($this->getKeywordArray()) == 0) {
			throw new \Exception('No keywords were entered.  Please enter at least one keyword.');
		}
		if (trim($this->getLink()) == "") {
			throw new \Exception('No links were entered.  Please enter at least one link.');
		}
		/* @var $ubot \Flux\Ubot */
		$ubot = new \Flux\Ubot();
		$ubot->setIgnorePagination(true);
		$ubots = $ubot->queryAll();

		$next_ping_time_in_seconds = 2;

		foreach ($this->getKeywordArray() as $keyword) {
			foreach ($ubots as $ubot) {
				foreach ($ubot->getUrls() as $url) {
					/* @var $ubot_item \Flux\UbotQueue */
					$ubot_item = new \Flux\UbotQueue();
					$ubot_item->setComment(\Flux\Comment::getRandomComment());
					$rando = \Flux\Rando::getRandom();
					$ubot_item->setName($rando->getName());
					$ubot_item->setEmail($rando->getEmail());
					$ubot_item->setLink($this->getLink());
					$ubot_item->setUrl($url);
					$ubot_item->setKeyword($keyword);
					$ubot_item->setUbot($ubot->getId());
					$ubot_item->setCreatedAt(new \MongoDate());
					$ubot_item->setExpireAt(new \MongoDate(strtotime("now + 1 day")));
					$ubot_item->insert();
					$rows_affected++;
				}
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