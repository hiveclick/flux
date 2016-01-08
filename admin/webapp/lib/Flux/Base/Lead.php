<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

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
	protected $_s; // array of assigned splits
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
		return $this;
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
	 * Returns the _s
	 * @return array
	 */
	function getS() {
		if (is_null($this->_s)) {
			$this->_s = array();
		}
		return $this->_s;
	}
	
	/**
	 * Sets the _s
	 * @var array
	 */
	function setS($arg0) {
		if (is_array($arg0)) {
		   $this->_s = $arg0;
		   array_walk($this->_s, function(&$value) {
			   if (is_array($value)) {
				   $tmp_value = new \Flux\LeadSplit();
				   $tmp_value->populate($value);
				   $value = $tmp_value;
			   }
		   });
		}
		$this->addModifiedColumn("_s");
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
		$ret_val = null;
		if (!is_null($data_field)) {
			if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
				// First check if the value exists in the dirty array
				if (isset($this->getD()->{$data_field->getKeyName()})) {
					$ret_val = ($this->getD()->{$data_field->getKeyName()});
				}
			} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
				// Next check if the value exists in the main data array
				foreach ($this->getE() as $key => $event) {
					if ($event->getDataField()->getId() == $data_field->getId()) {
						$ret_val = $event->getValue();   
					}
				}
			} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
				// First check if the value exists in the array
				$entry = preg_replace_callback("/_([a-zA-Z0-9])/", function($matches) { return strtoupper($matches[1]); }, strtolower($data_field->getKeyName()));
				$callableName = null;
				if (is_callable(array($this->getTracking(), 'get' . ucfirst($entry)), false, $callableName)) {
					$ret_val = $this->getTracking()->{'get' . ucfirst($entry)}();
				}
			}
			
			// Cast the return value if we need to
			if (!is_null($ret_val)) {		
				// Assign the default value if the data is not found
				if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY && !is_array($ret_val)) {
					if (is_string($ret_val)) {
						return array($ret_val);
					} else if (is_object($ret_val)) {
						return json_decode(json_encode($ret_val));
					}
				} else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_STRING && !is_string($ret_val)) {
					if (is_array($ret_val)) {
						return implode(",", $ret_val);
					} else if (is_object($ret_val)) {
						return json_encode($ret_val);
					}
				} else {
					return $ret_val;
				}
			} else {
				if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_ARRAY && is_string($default_value)) {
					$default_value = array($default_value);
				} else if ($data_field->getFieldType() == \Flux\DataField::DATA_FIELD_TYPE_STRING && is_array($default_value)) {
					$default_value = implode(", ", $default_value);
				}
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
	 * Updates just the notes on a lead
	 * @return integer
	 */
	function updateNotes() {
		return parent::update(array(), array('$set' => array('notes' => $this->getNotes())));
	}
	
	/**
	 * Updates the lead by first saving the lead, then saving the _d, _e, and _t variables into the lead
	 * @return boolean
	 */
	public function update($criteria_array = array(), $update_array = array(), $options_array = array('upsert' => true), $use_set_notation = false) {
  
		// First update the data array
		$data_criteria = array('_id' => $this->getId());
		$data_update = array('modified' => new \MongoDate());
		foreach ($this->getD() as $key => $value) {
			$data_update[\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $key] = $value;
		}
		if (!empty($data_update)) {
			$this->getCollection()->update($data_criteria, array('$set' => $data_update), array('upsert' => true));
		}
		
		// Now update events
		foreach ($this->getE() as $lead_event) {
			try {
				$event_criteria = array('_id' => $this->getId(), \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.data_field.data_field_id' => $lead_event->getDataField()->getId());
				$event_update = array('$set' => array(\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.$.v' => $lead_event->getValue(), \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.$.t' => $lead_event->getT()));
				$ret_val = $this->getCollection()->update($event_criteria, $event_update, array('upsert' => true));
				if ($ret_val == 0) {
					throw new \Exception('The positional operator did not find the match needed from the query');
				}
			} catch (\Exception $e) {
				if (strpos($e->getMessage(), 'The positional operator did not find the match needed from the query') !== false) {
					// The domain group was not found, so push it
					$event_criteria = array('_id' => $this->getId(), \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.data_field.data_field_id' => array('$ne' => $lead_event->getDataField()->getId()));
					$event_update = array('$addToSet' => array(\Flux\DataField::DATA_FIELD_EVENT_CONTAINER => $lead_event->toArray(true, true, true)));
					$this->getCollection()->update($event_criteria, $event_update, array('upsert' => true));
				} else {
					
				}
			}
		}
		
		// Now save the tracking data
		$tracking_criteria = array('_id' => $this->getId());
		$tracking_update = array('$set' => array('_t' => $this->getTracking()->toArray(true, true, true)));
		$this->getCollection()->update($tracking_criteria, $tracking_update, array('upsert' => true));
		
		return true;
	}
	
	/**
	 * Adds an event to this lead
	 * @param string $event_key
	 * @return \Flux\LocalLead
	 */
	protected function addEvent($event_key, $value = 1) {
		if (is_string($event_key)) {
			$lead_event = new \Flux\LeadEvent();
			$lead_event->setDataField($event_key);
			$lead_event->setValue($value);
			$lead_event->setT(new \MongoDate());
			$lead_event->setClient($this->getTracking()->getClient()->getId());
			$lead_event->setOffer($this->getTracking()->getOffer()->getId());
			
			// Find the payout and revenue
			/* @todo change this from an array to an object */
			/*
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
			*/
			
			$events = $this->getE();
			$events[] = $lead_event;
			$this->setE($events);
		}
		return $this;
	}
	
	/**
	 * Creates indexes for this collection
	 * @return boolean
	 */
	static function ensureIndexes() {
		$exception = null;
		$indexes = array();
		$indexes[] = array('idx' => array(\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.offer._id' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array(\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.client._id' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array(\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t' => -1, \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.data_field.data_field_key_name' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.em' => 1), 'options' => array('background' => true));
		$indexes[] = array('idx' => array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.name' => 1, \Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.fn' => 1, \Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.ln' => 1), 'options' => array('background' => true));

		foreach ($indexes as $index) {
			try {
				$collection = new self();
				$collection->getCollection()->createIndex($index['idx'], $index['options']);
			} catch (\Exception $e) {
				$exception = $e;
			}
		}
	
		// Enable sharding as well
		/*
		$shards[] = array('idx' => array('_id' => 1));
		foreach ($shards as $shard) {
			try {
				$collection = new self();
	
				$shardDB = \Mojavi\Controller\Controller::getInstance()->getContext()->getDatabaseManager()->getDatabase($collection->getDbName())->getParameter('database');
				$shardCollection =  $shardDB . '.' . $collection->getCollectionName();
	
				$r = $collection->getConnection('mongo_admin')->command(array('shardCollection' => $shardCollection, 'key' => $shard['idx']));
	
				if (isset($r['ok']) && $r['ok'] == 0 && isset($r['errmsg'])) {
					throw new \Exception($r['errmsg']);
				}
			} catch (\Exception $e) {
				if (strpos($e->getMessage(), 'already sharded') === false && strpos($e->getMessage(), 'no such command') === false) {
					$exception = $e;
				}
			}
		}
		*/	
		if (!is_null($exception)) { throw $exception; }
	}
}
