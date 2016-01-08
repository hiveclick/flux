<?php
namespace Flux\Base;

use Mojavi\Form\CommonForm;

class Search extends CommonForm {
	
	const SEARCH_TYPE_LEAD = 1;
	const SEARCH_TYPE_OFFER = 2;
	const SEARCH_TYPE_CAMPAIGN = 3;
	const SEARCH_TYPE_FULFILLMENT = 4;
	const SEARCH_TYPE_LEAD_SPLIT = 5;
	
	protected $optgroup;
	protected $search_type;
	protected $name;
	protected $url;
	protected $description;
	protected $meta;
	
	/**
	 * Returns the optgroup
	 * @return string
	 */
	function getOptgroup() {
		if (is_null($this->optgroup)) {
			$this->optgroup = "leads";
		}
		return $this->optgroup;
	}
	
	/**
	 * Sets the optgroup
	 * @var string
	 */
	function setOptgroup($arg0) {
		$this->optgroup = $arg0;
		$this->addModifiedColumn("optgroup");
		return $this;
	}
	
	/**
	 * Returns the search_type
	 * @return integer
	 */
	function getSearchType() {
		if (is_null($this->search_type)) {
			$this->search_type = self::SEARCH_TYPE_LEAD;
		}
		return $this->search_type;
	}
	
	/**
	 * Sets the search_type
	 * @var integer
	 */
	function setSearchType($arg0) {
		$this->search_type = $arg0;
		if ($this->search_type == self::SEARCH_TYPE_LEAD) {
			$this->setOptgroup('leads');
		} else if ($this->search_type == self::SEARCH_TYPE_CAMPAIGN) {
			$this->setOptgroup('campaigns');
		} else if ($this->search_type == self::SEARCH_TYPE_FULFILLMENT) {
			$this->setOptgroup('fulfillments');
		} else if ($this->search_type == self::SEARCH_TYPE_LEAD_SPLIT) {
			$this->setOptgroup('lead splits');
		} else {
			$this->setOptgroup('offers');
		}
		$this->addModifiedColumn("search_type");
		return $this;
	}
	
	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}
	
	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		$this->name = $arg0;
		$this->addModifiedColumn("name");
		return $this;
	}
	
	/**
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = "";
		}
		return $this->description;
	}
	
	/**
	 * Sets the description
	 * @var string
	 */
	function setDescription($arg0) {
		$this->description = $arg0;
		$this->addModifiedColumn("description");
		return $this;
	}
	
	/**
	 * Returns the meta
	 * @return string
	 */
	function getMeta() {
		if (is_null($this->meta)) {
			$this->meta = "";
		}
		return $this->meta;
	}
	
	/**
	 * Sets the meta
	 * @var string
	 */
	function setMeta($arg0) {
		$this->meta = $arg0;
		$this->addModifiedColumn("meta");
		return $this;
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