<?php
namespace Flux;

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
	protected $_exports; // array of \Flux\LeadExport
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
				/*
			    // Handle our main data array
			    if (($key == \Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER) && is_array($value)) {
			    	foreach ($value as $key => $val) {
			    	    $this->getD()->{$key} = $val;
			    	}
			    	if ($modify_columns) { $this->addModifiedColumn(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER); }
			    	continue;
			    }
    		
        		// Handle the _t tracking array
                if (($key == \Flux\DataField::DATA_FIELD_TRACKING_CONTAINER) && is_array($value)) {
        			$this->setT($value);
        			if ($modify_columns) { $this->addModifiedColumn(\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER); }
        			continue;
        		}
        		*/
			}
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
	 * Returns the _offer_id
	 * @return integer
	 */
	function getOfferId() {
		return $this->getTracking()->getOfferId();
	}
	
	/**
	 * Sets the offer_id
	 * @var integer
	 */
	function setO($arg0) {
		$this->getTracking()->setOfferId($arg0);
		return $this;
	}

	/**
	 * Sets the offer_id
	 * @var integer
	 */
	function setOfferId($arg0) {
		$this->getTracking()->setOfferId($arg0);
		return $this;
	}

	/**
	 * Returns the _client_id
	 * @return integer
	 */
	function getClientId() {
		return $this->getTracking()->getClientId();
	}
	
	/**
	 * Sets the _client_id
	 * @var integer
	 */
	function setC($arg0) {
		$this->getTracking()->setClientId($arg0);
		return $this;
	}

	/**
	 * Sets the _client_id
	 * @var integer
	 */
	function setClientId($arg0) {
		$this->getTracking()->setClientId($arg0);
		return $this;
	}
	
	/**
	 * Sets the _ck
	 * @var integer
	 */
	function setCk($arg0) {
		$this->getTracking()->setCampaignId($arg0);
		return $this;
	}

	/**
	 * Returns the _campaign_id
	 * @return string
	 */
	function getCampaignId() {
		return $this->getTracking()->getCampaignId();
	}

	/**
	 * Sets the _campaign_id
	 * @var string
	 */
	function setCampaignId($arg0) {
		$this->getTracking()->setCampaignId($arg0);
		return $this;
	}

	/**
	 * Returns the offer (from the cache or it pulls it via the api)
	 * @return \FluxFE\Offer
	 */
	function getOffer() {
		return $this->getTracking()->getOffer();
	}

	/**
	 * Returns the client (from the cache or it pulls it via the api)
	 * @return \FluxFE\Client
	 */
	function getClient() {
		return $this->getTracking()->getClient();
	}

	/**
	 * Returns the campaign (from the cache or it pulls it via the api)
	 * @return \FluxFE\Campaign
	 */
	function getCampaign() {
		return $this->getTracking()->getCampaign();
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
					if ($event->getEventId() == $data_field->getKeyName()) {
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
			// if we are incrementing, decrementing, min, or max'ing then make sure that the data field supports it
			if (in_array($request_code, array(self::LEAD_REQUEST_CODE_INC, self::LEAD_REQUEST_CODE_DEC))) {
				if (!in_array($data_field->getType(), \Flux\DataField::typesCanIncDec())) {
					$this->getErrors()->addError('warning', 'The dataField ' . $data_field->getName() . ' cannot be incremented or decremented');
					return false;
				}
			} else if (in_array($request_code, array(self::LEAD_REQUEST_CODE_MAX, self::LEAD_REQUEST_CODE_MIN))) {
				if (!in_array($data_field->getType(), \Flux\DataField::typesCanMaxMin())) {
					$this->getErrors()->addError('warning', 'The dataField ' . $data_field->getName() . ' cannot be maximized or minimized');
					return false;
				}
			}
	
			//unset operations shouldn't need any validation
			if ($request_code === self::LEAD_REQUEST_CODE_UNSET) {
				$result = true;
			} else {
				$result = $data_field->doValidationAndFormat($data_value, $this);
			}
	
			if ($result === true) {
				if ($data_field->getStorageType() === \Flux\DataField::DATA_FIELD_STORAGE_TYPE_MAIN) {
					// right now we do nothing with these
				} else if($data_field->getStorageType() === \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
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
				$this->getErrors()->addError('warning', 'The value passed for ' . $data_field->getName() . ' is not valid');
				LoggerManager::error(__METHOD__ . " :: " . "The value passed for " . $data_field->getName() . " is not valid");
				return false;
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
				if ($event->getEventId() == $lead_event->getEventId()) {
					// Event already exists, so skip it
				    LoggerManager::error(__METHOD__ . " :: " . "EVENT ALREADY EXISTS: " . $lead_event->getEventId());
					return $this;
				}
			}
			$events = $this->getE();
			$events[] = $lead_event;
			$this->setE($events);
		} else if (is_string($event_key)) {
		    foreach ($this->getE() as $event) {
		    	if ($event->getEventId() == trim($event_key)) {
		    		// Event already exists, so skip it
		    		return $this;
		    	}
		    }
			$data_field = $event_key;
			if (!is_null($data_field)) {
				// Find the payout and revenue
				$offer_events = $this->getOffer()->getEvents();
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
				$lead_event = new \Flux\LeadEvent();
				$lead_event->setClientId($this->getClientId());
				$lead_event->setOfferId($this->getOfferId());
				$lead_event->setEventId($event_key);
				$lead_event->setValue(1);
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
					$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.n'] = $data_field->getKeyName();					
				}
			}
		}
		
		if (count($this->getOfferIdArray()) > 0) {
			$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . \Flux\DataField::DATA_FIELD_REF_OFFER_ID . '._id'] = array('$in' => $this->getOfferIdArray());
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
