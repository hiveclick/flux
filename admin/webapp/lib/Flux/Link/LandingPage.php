<?php
namespace Flux\Link;

class LandingPage extends BasicLink {

	protected $url;
	
	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		return parent::getName();
	}
	
	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the url
	 * @return string
	 */
	function getUrl() {
		if (is_null($this->url)) {
			$this->url = "";
		}
		return $this->url;
	}
	
	/**
	 * Sets the url
	 * @var string
	 */
	function setUrl($arg0) {
		$this->url = $arg0;
		$this->addModifiedColumn("url");
		return $this;
	}
}