<?php
/**
 * UbotQueue.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   9/1/16 10:48 AM
 */

namespace Flux\Base;


use Mojavi\Form\MongoForm;

class UbotQueue extends MongoForm
{

	protected $ubot;

	protected $keyword;
	protected $comment;
	protected $link;
	protected $name;
	protected $email;

	protected $is_processed;
	protected $is_error;
	protected $error_message;
	protected $process_time;
	protected $attempts;
	protected $screenshot_url;
	protected $expire_at;
	protected $created_at;
	protected $processed_at;
	protected $next_attempt_at;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('ubot_queue');
		$this->setDbName('admin');
	}

	/**
	 * @return \Flux\Link\Ubot
	 */
	public function getUbot()
	{
		if (is_null($this->ubot)) {
			$this->ubot = new \Flux\Link\Ubot();
		}
		return $this->ubot;
	}

	/**
	 * @param mixed $ubot
	 */
	public function setUbot($ubot)
	{
		if ($ubot instanceof \Flux\Link\Ubot) {
			$this->ubot = $ubot;
		} else if ($ubot instanceof \Flux\Ubot) {
			$this->ubot = new \Flux\Link\Ubot();
			$this->ubot->setId($ubot->getId());
		} else if (is_array($ubot)) {
			$this->ubot = new \Flux\Link\Ubot();
			$this->ubot->populate($ubot);
		} else if ($ubot instanceof \MongoId) {
			$this->ubot = new \Flux\Link\Ubot();
			$this->ubot->setId($ubot);
		} else if (is_string($ubot) && \MongoId::isValid($ubot)) {
			$this->ubot = new \Flux\Link\Ubot();
			$this->ubot->setId($ubot);
		}
		$this->addModifiedColumn("ubot");
	}

	/**
	 * @return mixed
	 */
	public function getKeyword()
	{
		if (is_null($this->keyword)) {
			$this->keyword = "";
		}
		return $this->keyword;
	}

	/**
	 * @param mixed $keyword
	 */
	public function setKeyword($keyword)
	{
		$this->keyword = $keyword;
		$this->addModifiedColumn("keyword");
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

	/**
	 * @return mixed
	 */
	public function getIsProcessed()
	{
		if (is_null($this->is_processed)) {
			$this->is_processed = false;
		}
		return $this->is_processed;
	}

	/**
	 * @param mixed $is_processed
	 */
	public function setIsProcessed($is_processed)
	{
		$this->is_processed = (boolean)$is_processed;
		$this->addModifiedColumn("is_processed");
	}

	/**
	 * @return mixed
	 */
	public function getIsError()
	{
		if (is_null($this->is_error)) {
			$this->is_error = false;
		}
		return $this->is_error;
	}

	/**
	 * @param mixed $is_error
	 */
	public function setIsError($is_error)
	{
		$this->is_error = (boolean)$is_error;
		$this->addModifiedColumn("is_error");
	}

	/**
	 * @return mixed
	 */
	public function getErrorMessage()
	{
		if (is_null($this->error_message)) {
			$this->error_message = "";
		}
		return $this->error_message;
	}

	/**
	 * @param mixed $error_message
	 */
	public function setErrorMessage($error_message)
	{
		$this->error_message = $error_message;
		$this->addModifiedColumn("error_message");
	}

	/**
	 * @return mixed
	 */
	public function getProcessTime()
	{
		if (is_null($this->process_time)) {
			$this->process_time = new \MongoDate();
		}
		return $this->process_time;
	}

	/**
	 * @param mixed $process_time
	 */
	public function setProcessTime($process_time)
	{
		if ($process_time instanceof \MongoDate) {
			$this->process_time = $process_time;
		} else if (is_int($process_time)) {
			$this->process_time = new \MongoDate($process_time);
		} else if (is_string($process_time)) {
			$this->process_time = new \MongoDate(strtotime($process_time));
		}
		$this->addModifiedColumn("process_time");
	}

	/**
	 * @return mixed
	 */
	public function getAttempts()
	{
		if (is_null($this->attempts)) {
			$this->attempts = 0;
		}
		return $this->attempts;
	}

	/**
	 * @param mixed $attempts
	 */
	public function setAttempts($attempts)
	{
		$this->attempts = (int)$attempts;
		$this->addModifiedColumn("attempts");
	}

	/**
	 * @return mixed
	 */
	public function getScreenshotUrl()
	{
		if (is_null($this->screenshot_url)) {
			$this->screenshot_url = "";
		}
		return $this->screenshot_url;
	}

	/**
	 * @param mixed $screenshot_url
	 */
	public function setScreenshotUrl($screenshot_url)
	{
		$this->screenshot_url = $screenshot_url;
		$this->addModifiedColumn("screenshot_url");
	}

	/**
	 * @return mixed
	 */
	public function getExpireAt()
	{
		if (is_null($this->expire_at)) {
			$this->expire_at = new \MongoDate(strtotime('now + 1 day'));
		}
		return $this->expire_at;
	}

	/**
	 * @param mixed $expire_at
	 */
	public function setExpireAt($expire_at)
	{
		if ($expire_at instanceof \MongoDate) {
			$this->expire_at = $expire_at;
		} else if (is_int($expire_at)) {
			$this->expire_at = new \MongoDate($expire_at);
		} else if (is_string($expire_at)) {
			$this->expire_at = new \MongoDate(strtotime($expire_at));
		}
		$this->addModifiedColumn("expire_at");
	}

	/**
	 * @return mixed
	 */
	public function getProcessedAt()
	{
		if (is_null($this->processed_at)) {
			$this->processed_at = new \MongoDate();
		}
		return $this->processed_at;
	}

	/**
	 * @param mixed $processed_at
	 */
	public function setProcessedAt($processed_at)
	{
		if ($processed_at instanceof \MongoDate) {
			$this->processed_at = $processed_at;
		} else if (is_int($processed_at)) {
			$this->processed_at = new \MongoDate($processed_at);
		} else if (is_string($processed_at)) {
			$this->processed_at = new \MongoDate(strtotime($processed_at));
		}
		$this->addModifiedColumn("processed_at");
	}

	/**
	 * @return mixed
	 */
	public function getCreatedAt()
	{
		if (is_null($this->created_at)) {
			$this->created_at = new \MongoDate();
		}
		return $this->created_at;
	}

	/**
	 * @param mixed $created_at
	 */
	public function setCreatedAt($created_at)
	{
		if ($created_at instanceof \MongoDate) {
			$this->created_at = $created_at;
		} else if (is_int($created_at)) {
			$this->created_at = new \MongoDate($created_at);
		} else if (is_string($created_at)) {
			$this->created_at = new \MongoDate(strtotime($created_at));
		}
		$this->addModifiedColumn("created_at");
	}

	/**
	 * @return mixed
	 */
	public function getNextAttemptAt()
	{
		if (is_null($this->next_attempt_at)) {
			$this->next_attempt_at = new \MongoDate();
		}
		return $this->next_attempt_at;
	}

	/**
	 * @param mixed $next_attempt_at
	 */
	public function setNextAttemptAt($next_attempt_at)
	{
		if ($next_attempt_at instanceof \MongoDate) {
			$this->next_attempt_at = $next_attempt_at;
		} else if (is_int($next_attempt_at)) {
			$this->next_attempt_at = new \MongoDate($next_attempt_at);
		} else if (is_string($next_attempt_at)) {
			$this->next_attempt_at = new \MongoDate(strtotime($next_attempt_at));
		}
		$this->addModifiedColumn("next_attempt_at");
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->addModifiedColumn("name");
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		if (is_null($this->email)) {
			$this->email = "";
		}
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		$this->addModifiedColumn("email");
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
}