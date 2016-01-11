<?php
namespace Flux\Link;

class FlowRule extends BasicLink {
	
	protected $percent;
	protected $cap;
	protected $s4;
	protected $s5;
	protected $landing_page;
	protected $daily_click_count;

	/**
	 * Returns the daily_click_count
	 * @return integer
	 */
	function getDailyClickCount() {
		if (is_null($this->daily_click_count)) {
			$this->daily_click_count = 0;
		}
		return $this->daily_click_count;
	}
	
	/**
	 * Sets the daily_click_count
	 * @var integer
	 */
	function setDailyClickCount($arg0) {
		$this->daily_click_count = (int)$arg0;
		$this->addModifiedColumn("daily_click_count");
		return $this;
	}
	
	/**
	 * Returns the percent
	 * @return float
	 */
	function getPercent() {
		if (is_null($this->percent)) {
			$this->percent = 0.00;
		}
		return $this->percent;
	}
	
	/**
	 * Sets the percent
	 * @var float
	 */
	function setPercent($arg0) {
		$this->percent = floatval($arg0);
		$this->addModifiedColumn("percent");
		return $this;
	}
	
	/**
	 * Returns the cap
	 * @return integer
	 */
	function getCap() {
		if (is_null($this->cap)) {
			$this->cap = 0;
		}
		return $this->cap;
	}
	
	/**
	 * Sets the cap
	 * @var integer
	 */
	function setCap($arg0) {
		$this->cap = (int)$arg0;
		$this->addModifiedColumn("cap");
		return $this;
	}
	
	/**
	 * Returns the s4
	 * @return string
	 */
	function getS4() {
		if (is_null($this->s4)) {
			$this->s4 = "";
		}
		return $this->s4;
	}
	
	/**
	 * Sets the s4
	 * @var string
	 */
	function setS4($arg0) {
		$this->s4 = $arg0;
		$this->addModifiedColumn("s4");
		return $this;
	}
	
	/**
	 * Returns the s5
	 * @return string
	 */
	function getS5() {
		if (is_null($this->s5)) {
			$this->s5 = "";
		}
		return $this->s5;
	}
	
	/**
	 * Sets the s5
	 * @var string
	 */
	function setS5($arg0) {
		$this->s5 = $arg0;
		$this->addModifiedColumn("s5");
		return $this;
	}
	
	/**
	 * Returns the landing_page
	 * @return string
	 */
	function getLandingPage() {
		if (is_null($this->landing_page)) {
			$this->landing_page = "";
		}
		return $this->landing_page;
	}
	
	/**
	 * Sets the landing_page
	 * @var string
	 */
	function setLandingPage($arg0) {
		$this->landing_page = $arg0;
		$this->addModifiedColumn("landing_page");
		return $this;
	}	
}