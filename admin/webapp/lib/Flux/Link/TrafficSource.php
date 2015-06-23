<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class TrafficSource extends CommonForm {
	
	protected $traffic_source_id;
	protected $traffic_source_name;
	protected $traffic_source_icon;
	protected $record_count;
	
	private $traffic_source;
	
	/**
	 * Returns the traffic_source_id
	 * @return integer
	 */
	function getTrafficSourceId() {
		if (is_null($this->traffic_source_id)) {
			$this->traffic_source_id = null;
		}
		return $this->traffic_source_id;
	}
	
	/**
	 * Sets the traffic_source_id
	 * @var integer
	 */
	function setTrafficSourceId($arg0) {
        if (is_string($arg0) && \MongoId::isValid($arg0)) {
	        $this->traffic_source_id = new \MongoId($arg0);
	    } else if ($arg0 instanceof \MongoId) {
	        $this->traffic_source_id = $arg0;
	    }
		return $this;
	}
	
	/**
	 * Returns the traffic_source_name
	 * @return string
	 */
	function getTrafficSourceName() {
		if (is_null($this->traffic_source_name)) {
			$this->traffic_source_name = "";
		}
		return $this->traffic_source_name;
	}
	
	/**
	 * Sets the traffic_source_name
	 * @var string
	 */
	function setTrafficSourceName($arg0) {
		$this->traffic_source_name = $arg0;
		return $this;
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
			$this->traffic_source->setId($this->getTrafficSourceId());
			$this->traffic_source->query();
		}
		return $this->traffic_source;
	}
}