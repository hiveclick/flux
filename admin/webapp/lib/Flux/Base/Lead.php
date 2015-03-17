<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;
use Mojavi\Logging\LoggerManager;

class Lead extends MongoForm {

	const LEAD_SAVE_FLAG = '__save';
	const LEAD_CLEAR_FLAG = '__clear';
	const LEAD_RETURN_DEFAULTS = '__dreturn';
	const LEAD_RETURN_EVENTS = '__ereturn';

	const LEAD_INSERT_FLAG = '__insert';
	const LEAD_UPDATE_FLAG = '__update';
	const LEAD_UNSET_FLAG = '__unset';
	const LEAD_INC_FLAG = '__inc';
	const LEAD_DEC_FLAG = '__dec';

	const LEAD_REQUEST_CODE_DEFAULT = 1;
	const LEAD_REQUEST_CODE_INSERT = 2;
	const LEAD_REQUEST_CODE_UPDATE = 3;
	const LEAD_REQUEST_CODE_UNSET = 4;
	const LEAD_REQUEST_CODE_INC = 5;
	const LEAD_REQUEST_CODE_DEC = 6;
	const LEAD_REQUEST_CODE_MAX = 7;
	const LEAD_REQUEST_CODE_MIN = 8;

	//this is used because mongo has problems making indexes on field names that are numeric only
	//if we ever want to make an index on "state", for example, this won't be possible unless we have a character prefix to make it indexable
	const LEAD_DATA_FIELD_PREFIX = 'd';

	/* Used to store the current campaign, offer and client */
	protected $_d; // array of internal data
	protected $_e; // array of \Flux\LeadEvent
	protected $_t; // \Flux\LeadTracking
	protected $notes;
	protected $fulfill_log_contents;
	protected $fulfill_export_id;
	
	private $required_fields;
	private $offer_id_array;
	

	public function __construct() {
		$this->setCollectionName('lead');
		$this->setDbName('lead');
		$this->setIdType(self::ID_TYPE_MONGO);
	}

	/**
	 * Populates the hidden _d and _e variables
	 * @return boolean
	 */
	function populate($arg0, $modify_columns = true) {
		parent::populate($arg0, $modify_columns);
		if (is_array($arg0)) {
			foreach ($arg0 as $key => $value) {
				$this->setValue($key, $value);
			}
		} else {
			$this->setValue($key, $value);
		}
	}
	
	/**
	 * Returns the _notes
	 * @return array
	 */
	function getNotes() {
		if (is_null($this->notes)) {
			$this->notes = array();
		}
		return $this->notes;
	}
	
	/**
	 * Sets the _notes
	 * @var array
	 */
	function setNotes($arg0) {
		if (is_array($arg0)) {
		   $this->notes = $arg0;
		} else if (is_string($arg0)) {
			$this->addNote($arg0);
		}
		$this->addModifiedColumn("notes");
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
	 * Returns the _exports
	 * @return array
	 */
	function getExports() {
		if (is_null($this->_exports)) {
			$this->_exports = array();
		}
		return $this->_exports;
	}
	
	/**
	 * Sets the _exports
	 * @var array
	 */
	function setExports($arg0) {
		$this->_exports = $arg0;
		$this->addModifiedColumn("_exports");
		return $this;
	}
	
	/**
	 * Adds a new export
	 * @var $lead_export
	 */
	function addExport($lead_export) {
		$tmp_array = $this->getExports();
		$tmp_array[] = $lead_export;
		return $this->setExports($tmp_array);
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
	 * Returns the _t
	 * @return array
	 */
	function getT() {
		if (is_null($this->_t)) {
			$this->_t = new \Flux\LeadTracking();
		}
		return $this->_t;
	}

	/**
	 * Sets the _t
	 * @var array
	 */
	function setT($arg0) {
	    if (is_array($arg0)) {
	        $this->getTracking()->populate($arg0);
	    } else if ($arg0 instanceof \Flux\LeadTracking) {
	        $this->_t = $arg0;
	    }
		$this->addModifiedColumn('_t');
		return $this;
	}

	/**
	 * Returns the _d
	 * @return array|object
	 */
	function getD() {
		if (is_null($this->_d)) {
			$this->_d = array();
		}
		return (object)$this->_d;
	}

	/**
	 * Sets the __d
	 * @var array|object
	 */
	function setD($arg0) {
		$this->_d = (array)$arg0;
		$this->addModifiedColumn('_d');
		return $this;
	}

	/**
	 * Returns the _e
	 * @return array|object
	 */
	function getE() {
		if (is_null($this->_e)) {
			$this->_e = array();
		}
		return $this->_e;
	}

	/**
	 * Sets the _e
	 * @var array|object
	 */
	function setE($arg0) {
	    if (is_array($arg0)) {
	        $this->_e = $arg0;
	        array_walk($this->_e, function(&$value) { 
	               if (is_array($value)) { 
	                   $tmp_value = new \Flux\LeadEvent();
	                   $tmp_value->populate($value);
	                   $value = $tmp_value;
	               }
	        });
	    }
		$this->addModifiedColumn('_e');
		return $this;
	}

	/**
	 * Converts a request code to a mongo statement
	 * @param integer $request_code
	 * @return string
	 */
	public static function convertRequestCodeToOperator($request_code) {
		switch($request_code) {
			case self::LEAD_REQUEST_CODE_INSERT:
				return '$setOnInsert';
				break;
			case self::LEAD_REQUEST_CODE_UPDATE:
				return '$set';
				break;
			case self::LEAD_REQUEST_CODE_UNSET:
				return '$unset';
				break;
			case self::LEAD_REQUEST_CODE_INC:
				return '$inc';
				break;
			case self::LEAD_REQUEST_CODE_DEC:
				return '$dec';
				break;
			case self::LEAD_REQUEST_CODE_MAX:
				return '$max';
				break;
			case self::LEAD_REQUEST_CODE_MIN:
				return '$min';
				break;
		}
		return '$set';
	}
	
	/**
	 * Returns a data value from the lead
	 * @return string
	 */
	function getValue($data_name, $default_value = '', $assign_if_not_set = true) {
		$data_field = \Flux\DataField::retrieveDataFieldFromName($data_name);
		if (!is_null($data_field)) {
			if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
				// First check if the value exists in the dirty array
				if (isset($this->getD()->{$data_field->getKeyName()})) {
					return $this->getD()->{$data_field->getKeyName()};
				}
				// Assign the default value if the data is not found
				return $default_value;
			} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
				// Next check if the value exists in the main data array
				foreach ($this->getE() as $key => $event) {
					if ($event->getDataField()->getDataFieldId() == $data_field->getId()) {
						return $event->getValue();   
					}
				}
				return $default_value;
			} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
				// First check if the value exists in the array
				$entry = preg_replace_callback("/_([a-zA-Z0-9])/", function($matches) { return strtoupper($matches[1]); }, strtolower($data_field->getKeyName()));
				$callableName = null;
				if (is_callable(array($this->getTracking(), 'get' . ucfirst($entry)), false, $callableName)) {
					return $this->getTracking()->{'get' . ucfirst($entry)}();
				}
				// Assign the default value if the data is not found
				return $default_value;
			}
		}
		return $default_value;
	}
	
	/**
	 * Sets a data value in the lead
	 * @param string $data_name
	 * @param string $data_value
	 * @return \Flux\LocalLead
	 */
	function setValue($data_name, $data_value, $request_code = self::LEAD_REQUEST_CODE_UPDATE) {
		/* @var $data_field \Flux\DataField */
		$data_field = \Flux\DataField::retrieveDataFieldFromName($data_name);
		if (!is_null($data_field)) {			
			// Convert the value if we need to
			if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_STRING && !is_string($data_value)) {
				if (is_array($data_value) && count($data_value) <= 1) {
					$data_value = implode("", $data_value);
				} else if (is_array($data_value) && count($data_value) > 1) {
					$data_value = implode(", ", $data_value);
				} else {
					$data_value = (string)$data_value;
				}
			} else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY && !is_array($data_value)) {
				if (is_string($data_value)) {
					if (strpos($data_value, ",") !== false) {
						$data_value = explode(",", $data_value);
					} else {
						$data_value = array($data_value);	
					}
				} else {
					$data_value = array($data_value);
				}
			}
	
			// Now assign the value to the correct storate location
			if($data_field->getStorageType() === \Flux\DataField::DATA_FIELD_STORAGE_TYPE_MAIN || $data_field->getStorageType() === \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
				// save the data
				$d = $this->getD();
				$d->{$data_field->getKeyName()} = $data_value;
				$this->setD($d);
			} else if ($data_field->getStorageType() === \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
				// save the tracking data
				$tracking = $this->getT();
				$tracking->populate(array($data_field->getKeyName() => $data_value));
				$this->setT($tracking);
			} else if($data_field->getStorageType() === \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
				// Find the payout and revenue
				$this->addEvent($data_field->getKeyName(), $data_value);
			}
			return true;
		} else {
			// Ignore the key if it is just the default _d, _e, or _t
			if (!in_array($data_name, array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER, \Flux\DataField::DATA_FIELD_TRACKING_CONTAINER))) {
				#\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Cannot find data field for key " . $data_name);	
			}
		}
		return $this;
	}
	
	/**
	 * Adds an event to this lead
	 * @param string $event_key
	 * @return \Flux\LocalLead
	 */
	protected function addEvent($event_key, $value = 1) {
		if (is_array($event_key)) {
			$lead_event = new \Flux\LeadEvent();
			$lead_event->populate($event_key);
			foreach ($this->getE() as $event) {
				if ($event->getDataField()->getDataFieldId() == $lead_event->getDataField()->getDataFieldId()) {
					// Event already exists, so skip it
				    LoggerManager::error(__METHOD__ . " :: " . "EVENT ALREADY EXISTS: " . $lead_event->getDataField()->getDataFieldName());
					return $this;
				}
			}
			$events = $this->getE();
			$events[] = $lead_event;
			$this->setE($events);
		} else if (is_string($event_key)) {
		    foreach ($this->getE() as $event) {
		    	if ($event->getDataField()->getDataFieldName() == trim($event_key)) {
		    		// Event already exists, so skip it
		    		LoggerManager::error(__METHOD__ . " :: " . "EVENT ALREADY EXISTS: " . $event_key);
		    		return $this;
		    	}
		    }
			$data_field = $event_key;
			if (!is_null($data_field)) {
				// Find the payout and revenue
				$offer_events = $this->getTracking()->getOffer()->getOffer()->getEvents();
				$payout = 0.00;
				$revenue = 0.00;
				foreach ($offer_events as $offer_event) {
					if ($offer_event['event_id'] == $event_key) {
						if (isset($offer_event['field']) && $offer_event['field'] == 'payout' && isset($offer_event['value']) && floatval($offer_event['value']) > 0) {
							$payout = $offer_event['value'];
						}
						if (isset($offer_event['field']) && $offer_event['field'] == 'revenue' && isset($offer_event['value']) && floatval($offer_event['value']) > 0) {
							$revenue = $offer_event['value'];
						}
					}
				}
				$lead_event = new \Flux\Base\LeadEvent();
				$lead_event->setClient($this->getTracking()->getClient()->getClientId());
				$lead_event->setOffer($this->getTracking()->getOffer()->getOfferId());
				$lead_event->setDataField($event_key);
				$lead_event->setValue($value);
				$lead_event->setPayout($payout);
				$lead_event->setRevenue($revenue);
	
				$events = $this->getE();
				$events[] = $lead_event;
				$this->setE($events);
			}
		}
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
