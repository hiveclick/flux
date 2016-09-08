<?php
/**
 * Comment.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   8/19/16 10:37 AM
 */

namespace Flux;


class Comment extends Base\Comment
{
	static private $_comments;

	private $is_multicomment;

	/**
	 * @return mixed
	 */
	public function getIsMulticomment()
	{
		if (is_null($this->is_multicomment)) {
			$this->is_multicomment = false;
		}
		return $this->is_multicomment;
	}

	/**
	 * @param mixed $is_multicomment
	 */
	public function setIsMulticomment($is_multicomment)
	{
		$this->is_multicomment = (boolean)$is_multicomment;
		$this->addModifiedColumn("is_multicomment");
	}

	/**
	 * Inserts a new comment
	 */
	function insert() {
		if ($this->getIsMulticomment()) {
			// if we have to parse the comments, then do that first
			$comments = explode("\n", $this->getComment());
			foreach ($comments as $item) {
				$comment = new \Flux\Comment();
				$comment->setComment($item);
				$comment->setIsMulticomment(false);
				$comment->insert();
			}
		} else {
			return parent::insert();
		}
	}

	/**
	 * Returns a random comment
	 * @return string
	 */
	static function getRandomComment() {
		if (is_null(self::$_comments)) {
			$comment_obj = new \Flux\Comment();
			$comment_obj->setIgnorePagination(true);
			$comments = $comment_obj->queryAll();
			self::$_comments = array();
			foreach ($comments as $comment) {
				self::$_comments[] = $comment->getComment();
			}
		}
		// shuffle the comments
		shuffle(self::$_comments);
		// shift the first comment off the stack
		$ret_val = array_shift(self::$_comments);
		// push the first comment back onto the array
		array_push(self::$_comments, $ret_val);
		return $ret_val;
	}
}