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