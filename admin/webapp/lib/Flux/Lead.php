<?php
namespace Flux;

class Lead extends Base\Lead {

	protected $note;
	
	protected $fulfill_log_contents;
	protected $fulfill_export_id;
	
	private $pages;
	private $lead_splits;
	
	/**
	 * Returns the note
	 * @return string
	 */
	function getNote() {
		if (is_null($this->note)) {
			$this->note = "";
		}
		return $this->note;
	}
	
	/**
	 * Sets the note
	 * @var string
	 */
	function setNote($arg0) {
		$this->note = $arg0;
		return $this;
	}
	
	/**
	 * Returns the last open export
	 * @return \Flux\LeadExport
	 */
	function getLastExport() {
		if (count($this->getExports()) > 0) {
			return array_shift($this->getExports());
		} else {
			$lead_export = new \Flux\LeadExport();
			$lead_export->setLeadId($this->getId());
			$this->addExport($lead_export);
			return $lead_export;
		}
	}
	
	/**
	 * Returns the _t
	 * @return array
	 */
	function getTracking() {
		return $this->getT();
	}
	
	/**
	 * Returns the fulfill_log_contents
	 * @return string
	 */
	function getFulfillLogContents() {
		if (is_null($this->fulfill_log_contents)) {
			$this->fulfill_log_contents = "";
		}
		return $this->fulfill_log_contents;
	}
	
	/**
	 * Sets the fulfill_log_contents
	 * @var string
	 */
	function setFulfillLogContents($arg0) {
		$this->fulfill_log_contents = $arg0;
		$this->addModifiedColumn("fulfill_log_contents");
		return $this;
	}
	
	/**
	 * Returns the fulfill_export_id
	 * @return integer
	 */
	function getFulfillExportId() {
		if (is_null($this->fulfill_export_id)) {
			$this->fulfill_export_id = 0;
		}
		return $this->fulfill_export_id;
	}
	
	/**
	 * Sets the fulfill_export_id
	 * @var integer
	 */
	function setFulfillExportId($arg0) {
		$this->fulfill_export_id = (int)$arg0;
		$this->addModifiedColumn("fulfill_export_id");
		return $this;
	}

	/**
	 * Returns the lead name
	 * @return string
	 */
	function getLeadName() {
		if ($this->getValue('name') != '') {
			return $this->getValue('name');
		} else if ($this->getValue('fn') != '' || $this->getValue('ln') != '') {
			return trim($this->getValue('fn') . ' ' . $this->getValue('ln'));
		} else {
			return $this->getId();
		}
	}

	/**
	 * Helper method to find the derived firstname
	 * @return string
	 */
	function getDerivedFirstname() {
		if ($this->getValue('fn') != '') {
			return $this->getValue('fn');
		} else if ($this->getValue('name') != '') {
			return substr(trim($this->getValue('name')), 0, strpos(trim($this->getValue('name')), ' '));
		} else {
			return substr(trim($this->getLeadName()), 0, strpos(trim($this->getLeadName()), ' '));
		}
		return "";
	}

	/**
	 * Helper method to find the derived lastname
	 * @return string
	 */
	function getDerivedLastname() {
		if ($this->getValue('ln') != '') {
			return $this->getValue('ln');
		} else if ($this->getValue('name') != '') {
			return substr(trim($this->getValue('name')), strrpos(trim($this->getValue('name')), " ") + 1);
		} else {
			return substr(trim($this->getValue('name')), strrpos(trim($this->getValue('name')), " ") + 1);
		}
		return "";
	}
	
	/**
	 * Helper method to find the derived city
	 * @return string
	 */
	function getDerivedCity() {
		if ($this->getValue("cy") != "") {
			return $this->getValue("cy");
		}
		if ($this->getValue("zi") != "") {
			// do a lookup based on the zipcode
			$city = \Flux\Zip::lookupCity($this->getValue("zi"));
			return $city;
		}
		return "";
	}
	
	/**
	 * Helper method to find the derived state
	 * @return string
	 */
	function getDerivedState() {
		if ($this->getValue("st") != "") {
			return $this->getValue("st");
		}
		if ($this->getValue("zi") != "") {
			// do a lookup based on the zipcode
			$state = \Flux\Zip::lookupState($this->getValue("zi"));
			return $state;
		}
		return "";
	}
	
	/**
	 * Returns the pages
	 * @return array
	 */
	function getPages() {
		if (is_null($this->pages)) {
			$lead_page = new \Flux\LeadPage();
			$lead_page->setLead($this->getId());
			$lead_page->setIgnorePagination(true);
			$this->pages = $lead_page->queryAll();
		}
		return $this->pages;
	}
	
	/**
	 * Returns the lead_splits
	 * @return array
	 */
	function getLeadSplits($include_screenshots = false) {
		if (is_null($this->lead_splits)) {
			$lead_split = new \Flux\LeadSplit();
			$lead_split->setLead($this->getId());
			$lead_split->setIgnorePagination(true);
			if ($include_screenshots) {
				$this->lead_splits = $lead_split->queryAll(array(), array(), true);
			} else {
				$this->lead_splits = $lead_split->queryAll(array(), array('attempts.screenshot' => false, 'attempts.debug_screenshots' => false), true);
			}
		}
		return $this->lead_splits;
	}
}
