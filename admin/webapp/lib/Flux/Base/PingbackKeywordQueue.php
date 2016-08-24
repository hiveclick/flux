<?php
/**
 * PingbackKeywordQueue.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   8/18/16 4:39 PM
 */
namespace Flux\Base;

use \Mojavi\Form\MongoForm;

class PingbackKeywordQueue extends MongoForm {

	protected $pingback;
	protected $comment;
	protected $keyword;
	protected $url;

	protected $attempts;
	protected $next_pingback_time;

	protected $created_at;
	protected $processed_at;
	protected $is_processed;

	protected $raw_response;
	protected $response;

	protected $is_error;
	protected $error_message;

	protected $expire_at;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('pingback_keyword_queue');
		$this->setDbName('admin');
	}

	/**
	 * @return \Flux\Link\Pingback
	 */
	public function getPingback()
	{
		if (is_null($this->pingback)) {
			$this->pingback = new \Flux\Link\Pingback();
		}
		return $this->pingback;
	}

	/**
	 * @param mixed $pingback
	 */
	public function setPingback($pingback)
	{
		if ($pingback instanceof \Flux\Link\Pingback) {
			$this->pingback = $pingback;
		} else if (is_array($pingback)) {
			$this->pingback = new \Flux\Link\Pingback();
			$this->pingback->populate($pingback);
		} else if ($pingback instanceof \MongoId) {
			$this->pingback = new \Flux\Link\Pingback();
			$this->pingback->setId($pingback);
		} else if (is_string($pingback) && \MongoId::isValid($pingback)) {
			$this->pingback = new \Flux\Link\Pingback();
			$this->pingback->setId(new \MongoId($pingback));
		}
		$this->addModifiedColumn("pingback");
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
		$this->keyword = trim($keyword);
		$this->addModifiedColumn("keyword");
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		if (is_null($this->url)) {
			$this->url = "";
		}
		return $this->url;
	}

	/**
	 * @param mixed $url
	 */
	public function setUrl($url)
	{
		$this->url = trim($url);
		$this->addModifiedColumn("url");
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
	public function getAttempts()
	{
		if (is_null($this->attempts)) {
			$this->attempts = 1;
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
	public function getNextPingbackTime()
	{
		if (is_null($this->next_pingback_time)) {
			$this->next_pingback_time = new \MongoDate(strtotime('now + 16 seconds'));
		}
		return $this->next_pingback_time;
	}

	/**
	 * @param mixed $next_pingback_time
	 */
	public function setNextPingbackTime($next_pingback_time)
	{
		if ($next_pingback_time instanceof \MongoDate) {
			$this->next_pingback_time = $next_pingback_time;
		} else if (is_long($next_pingback_time)) {
			$this->next_pingback_time = new \MongoDate($next_pingback_time);
		} else if (is_string($next_pingback_time)) {
			$this->next_pingback_time = new \MongoDate(strtotime($next_pingback_time));
		}
		$this->addModifiedColumn("next_pingback_time");
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
	public function getRawResponse()
	{
		if (is_null($this->raw_response)) {
			$this->raw_response = "";
		}
		return $this->raw_response;
	}

	/**
	 * @param mixed $raw_response
	 */
	public function setRawResponse($raw_response)
	{
		$this->raw_response = $raw_response;
		$this->addModifiedColumn("raw_response");
	}

	/**
	 * @return mixed
	 */
	public function getResponse()
	{
		if (is_null($this->response)) {
			$this->response = "";
		}
		return $this->response;
	}

	/**
	 * @param mixed $response
	 */
	public function setResponse($response)
	{
		$this->response = $response;
		$this->addModifiedColumn("response");
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
}