<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class DomainGroup extends CommonForm {
	
	protected $domain_group_id;
	protected $domain_group_name;
	protected $record_count;
	
	private $domain_group;
	
	/**
	 * Returns the domain_group_id
	 * @return integer
	 */
	function getDomainGroupId() {
		if (is_null($this->domain_group_id)) {
			$this->domain_group_id = 0;
		}
		return $this->domain_group_id;
	}
	
	/**
	 * Sets the domain_group_id
	 * @var integer
	 */
	function setDomainGroupId($arg0) {
		$this->domain_group_id = (int)$arg0;
		return $this;
	}
	
	/**
	 * Returns the domain_group_name
	 * @return string
	 */
	function getDomainGroupName() {
		if (is_null($this->domain_group_name)) {
			$this->domain_group_name = "";
		}
		return $this->domain_group_name;
	}
	
	/**
	 * Sets the domain_group_name
	 * @var string
	 */
	function setDomainGroupName($arg0) {
		$this->domain_group_name = $arg0;
		return $this;
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