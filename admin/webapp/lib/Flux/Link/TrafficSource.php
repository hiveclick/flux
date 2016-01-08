<?php
namespace Flux\Link;

class TrafficSource extends BasicLink {
	
	protected $traffic_source_icon;
	protected $record_count;
	
	private $traffic_source;
	
	/**
	 * Returns the traffic_source_id
	 * @return integer
	 */
	function getTrafficSourceId() {
		return parent::getId();
	}
	
	/**
	 * Sets the traffic_source_id
	 * @var integer
	 */
	function setTrafficSourceId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the traffic_source_name
	 * @return string
	 */
	function getTrafficSourceName() {
		return parent::getName();
	}
	
	/**
	 * Sets the traffic_source_name
	 * @var string
	 */
	function setTrafficSourceName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the traffic_source_icon
	 * @return string
	 */
	function getTrafficSourceIcon() {
		if (is_null($this->traffic_source_icon)) {
			$this->traffic_source_icon = "";
		}
		return $this->traffic_source_icon;
	}
	
	/**
	 * Sets the traffic_source_icon
	 * @var string
	 */
	function setTrafficSourceIcon($arg0) {
		$this->traffic_source_icon = $arg0;
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
	 * Returns the traffic_source
	 * @return \Flux\TrafficSource
	 */
	function getTrafficSource() {
		if (is_null($this->traffic_source)) {
			$this->traffic_source = new \Flux\TrafficSource();
			$this->traffic_source->setId($this->getId());
			$this->traffic_source->query();
		}
		return $this->traffic_source;
	}
}