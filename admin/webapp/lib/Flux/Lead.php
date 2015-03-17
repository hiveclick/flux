<?php
namespace Flux;

class Lead extends Base\Lead {

	protected $note;
	
	protected $fulfill_log_contents;
	protected $fulfill_export_id;
	
	private $required_fields;
	private $offer_id_array;
	private $campaign_id_array;
	
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
	 * Sets the notes
	 * @var array
	 */
	function addNote($arg0) {
		$notes = $this->getNotes();
		$notes[] = array('t' => new \MongoDate(), 'note' => $arg0);
		$this->setNotes($notes);
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
	 * Returns the required_fields
	 * @return array
	 */
	function getRequiredFields() {
		if (is_null($this->required_fields)) {
			$this->required_fields = array();
		}
		return $this->required_fields;
	}
	
	/**
	 * Sets the required_fields
	 * @var array
	 */
	function setRequiredFields($arg0) {
		if (is_array($arg0)) {
		   $this->required_fields = $arg0;
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->required_fields = explode(",", $arg0);
			} else {
				$this->required_fields = array($arg0);
			}
		}
		return $this;
	}
	
	/**
	 * Returns the offer_id_array
	 * @return array
	 */
	function getOfferIdArray() {
		if (is_null($this->offer_id_array)) {
			$this->offer_id_array = array();
		}
		return $this->offer_id_array;
	}
	
	/**
	 * Sets the offer_id_array
	 * @var array
	 */
	function setOfferIdArray($arg0) {
		if (is_array($arg0)) {
			$this->offer_id_array = $arg0;
			array_walk($this->offer_id_array, function(&$val) { $val = (int)$val; });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->offer_id_array = explode(",", $arg0);
				array_walk($this->offer_id_array, function(&$val) { $val = (int)$val; });
			} else {
				$this->offer_id_array = array((int)$arg0);
			}
		}
		return $this;
	}
	
	/**
	 * Returns the campaign_id_array
	 * @return array
	 */
	function getCampaignIdArray() {
		if (is_null($this->campaign_id_array)) {
			$this->campaign_id_array = array();
		}
		return $this->campaign_id_array;
	}
	
	/**
	 * Sets the campaign_id_array
	 * @var array
	 */
	function setCampaignIdArray($arg0) {
		if (is_array($arg0)) {
			$this->campaign_id_array = $arg0;
			array_walk($this->campaign_id_array, function(&$val) { $val = trim($val); });
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->campaign_id_array = explode(",", $arg0);
				array_walk($this->campaign_id_array, function(&$val) { $val = trim($val); });
			} else {
				$this->campaign_id_array = array((int)$arg0);
			}
		}
		return $this;
	}
	
	/**
	 * Queries the leads
	 * @return array
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (trim($this->getKeywords()) != '') {
			$search_params = array();
			if (\MongoId::isValid(trim($this->getKeywords()))) {
				$search_params[] = array('_id' => new \MongoId($this->getKeywords()));
			}
			
			$firstname_data_field = \Flux\DataField::retrieveDataFieldFromKeyName('fn');
			$lastname_data_field = \Flux\DataField::retrieveDataFieldFromKeyName('ln');
			if (strpos($this->getKeywords(), ' ') === false) {
				$search_params[] = array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $firstname_data_field->getKeyName() =>  new \MongoRegex("/" . $this->getKeywords() . "/i"));
				$search_params[] = array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $lastname_data_field->getKeyName() =>  new \MongoRegex("/" . $this->getKeywords() . "/i"));
			} else {
				$firstname = substr($this->getKeywords(), 0, strpos($this->getKeywords(), ' '));
				$lastname = substr($this->getKeywords(), strpos($this->getKeywords(), ' ') + 1);
				$search_params[] = array('$and' => array(
					array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $firstname_data_field->getKeyName() =>  new \MongoRegex("/" . $firstname . "/i")),
					array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $lastname_data_field->getKeyName() =>  new \MongoRegex("/" . $lastname . "/i"))
				));
			}

			$criteria['$or'] = $search_params;
		}
		if (count($this->getRequiredFields()) > 0) {
			foreach ($this->getRequiredFields() as $required_field) {
				$data_field = \Flux\DataField::retrieveDataFieldFromKeyName($required_field);
				if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
					$criteria[\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $data_field->getKeyName()] = array('$exists' => '1');
				} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
					$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . $data_field->getKeyName()] = array('$exists' => '1');
				} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
					$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.data_field.data_field_id'] = $data_field->getId();					
				}
			}
		}
		
		if (count($this->getOfferIdArray()) > 0) {
			$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.offer.offer_id'] = array('$in' => $this->getOfferIdArray());
		}
		if (count($this->getCampaignIdArray()) > 0) {
			$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.campaign.campaign_id'] = array('$in' => $this->getCampaignIdArray());
		}
						
		return parent::queryAll($criteria, $hydrate);
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
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$lead = new \Flux\Lead();
		$lead->getCollection()->ensureIndex(array(\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t' => -1, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.o' => 1, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.n' => 1), array('background' => true));
		$lead->getCollection()->ensureIndex(array(\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.c' => 1, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t' => -1, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.o' => 1, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.n' => 1), array('background' => true));
		$lead->getCollection()->ensureIndex(array(\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.o' => 1, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t' => -1, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.n' => 1), array('background' => true));
		return true;
	}
}
