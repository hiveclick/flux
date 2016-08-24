<?php
/**
 * Comment.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   8/19/16 10:36 AM
 */

namespace Flux\Base;


use Mojavi\Form\MongoForm;

class Comment extends MongoForm
{
	protected $comment;

	/**
	 * Constructs new comment
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('comment');
		$this->setDbName('admin');
	}

	/**
	 * @return mixed
	 */
	public function getComment()
	{
		if (is_null($this->comment)) {
			$this->comment = "";
		}
		return $this->comment;
	}

	/**
	 * @param mixed $comment
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
		$this->addModifiedColumn("comment");
	}
}