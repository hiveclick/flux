<?php
namespace Flux\Link;

class DomainGroup extends BasicLink {
	
	protected $record_count;
	
	private $domain_group;
	
	/**
	 * Returns the domain_group_id
	 * @return integer
	 */
	function getDomainGroupId() {
		return parent::getId();
	}
	
	/**
	 * Sets the domain_group_id
	 * @var integer
	 */
	function setDomainGroupId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the domain_group_name
	 * @return string
	 */
	function getDomainGroupName() {
		return parent::getName();
	}
	
	/**
	 * Sets the domain_group_name
	 * @var string
	 */
	function setDomainGroupName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the record_count
	 * @return integer
	 */
	function getRecordCount() {
		if (is_null($this->record_count)) {
			$this->record_count = 0;
		}
		return $this->record_count;
	}
	
	/**
	 * Sets the record_count
	 * @var integer
	 */
	function setRecordCount($arg0) {
		$this->record_count = (int)$arg0;
		return $this;
	}
	
	/**
	 * Returns the domain_group
	 * @return \Flux\DomainGroup
	 */
	function getDomainGroup() {
		if (is_null($this->domain_group)) {
			$this->domain_group = new \Flux\DomainGroup();
			$this->domain_group->setId($this->getDomainGroupId());
			$this->domain_group->query();
		}
		return $this->domain_group;
	}
}