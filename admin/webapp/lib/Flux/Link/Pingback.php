<?php
/**
 * Pingback.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   8/18/16 4:44 PM
 */

namespace Flux\Link;


class Pingback extends BasicLink
{

	private $pingback;

	protected $url;

	/**
	 * Sets the id field
	 */
	function setId($arg0) {
		parent::setId($arg0);
		if (\MongoId::isValid($this->getId()) && $this->getName() == '') {
			$this->setName($this->getPingback()->getName());
		}
		if (\MongoId::isValid($this->getId()) && $this->getUrl() == '') {
			$this->setUrl($this->getPingback()->getUrl());
		}
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
		$this->url = $url;
		$this->addModifiedColumn("url");
	}

	/**
	 * Returns the pingback
	 * @return \Flux\Pingback
	 */
	function getPingback() {
		if (is_null($this->pingback)) {
			$this->pingback = new \Flux\Pingback();
			$this->pingback->setId($this->getId());
			$this->pingback->query();
		}
		return $this->pingback;
	}
}