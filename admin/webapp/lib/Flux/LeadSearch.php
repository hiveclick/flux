<?php
namespace Flux;

class LeadSearch extends Lead {
	
	private $required_fields;
	private $offer_id_array;
	private $campaign_id_array;
	
	private $click_start_date;
	private $click_end_date;
	
	private $created_start_date;
	private $created_end_date;
	
	private $conversion_start_date;
	private $conversion_end_date;
	
	private $fulfillment_start_date;
	private $fulfillment_end_date;
	
	private $conversion_date_range;
	
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
			array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->offer_id_array = explode(",", $arg0);
				array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
			} else {
				$this->offer_id_array = array($arg0);
				array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
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
			array_walk($this->campaign_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		} else if (is_string($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->campaign_id_array = explode(",", $arg0);
				array_walk($this->campaign_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
			} else {
				$this->campaign_id_array = array($arg0);
				array_walk($this->campaign_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
			}
		}
		return $this;
	}
	
	/**
	 * Returns the click_start_date
	 * @return string
	 */
	function getClickStartDate() {
		if (is_null($this->click_start_date)) {
			$this->click_start_date = "";
		}
		return $this->click_start_date;
	}
	
	/**
	 * Sets the click_start_date
	 * @var string
	 */
	function setClickStartDate($arg0) {
		$this->click_start_date = $arg0;
		$this->addModifiedColumn("click_start_date");
		return $this;
	}
	
	/**
	 * Returns the click_end_date
	 * @return string
	 */
	function getClickEndDate() {
		if (is_null($this->click_end_date)) {
			$this->click_end_date = "";
		}
		return $this->click_end_date;
	}
	
	/**
	 * Sets the click_end_date
	 * @var string
	 */
	function setClickEndDate($arg0) {
		$this->click_end_date = $arg0;
		$this->addModifiedColumn("click_end_date");
		return $this;
	}
	
	/**
	 * Returns the created_start_date
	 * @return string
	 */
	function getCreatedStartDate() {
		if (is_null($this->created_start_date)) {
			$this->created_start_date = "";
		}
		return $this->created_start_date;
	}
	
	/**
	 * Sets the created_start_date
	 * @var string
	 */
	function setCreatedStartDate($arg0) {
		$this->created_start_date = $arg0;
		$this->addModifiedColumn("created_start_date");
		return $this;
	}
	
	/**
	 * Returns the created_end_date
	 * @return string
	 */
	function getCreatedEndDate() {
		if (is_null($this->created_end_date)) {
			$this->created_end_date = "";
		}
		return $this->created_end_date;
	}
	
	/**
	 * Sets the created_end_date
	 * @var string
	 */
	function setCreatedEndDate($arg0) {
		$this->created_end_date = $arg0;
		$this->addModifiedColumn("created_end_date");
		return $this;
	}
	
	/**
	 * Returns the conversion_start_date
	 * @return string
	 */
	function getConversionStartDate() {
		if (is_null($this->conversion_start_date)) {
			$this->conversion_start_date = "";
		}
		return $this->conversion_start_date;
	}
	
	/**
	 * Sets the conversion_start_date
	 * @var string
	 */
	function setConversionStartDate($arg0) {
		if ($this->getConversionDateRange() == 0) {
			$this->conversion_start_date = $arg0;
		}
		$this->addModifiedColumn("conversion_start_date");
		return $this;
	}
	
	/**
	 * Returns the conversion_end_date
	 * @return string
	 */
	function getConversionEndDate() {
		if (is_null($this->conversion_end_date)) {
			$this->conversion_end_date = "";
		}
		return $this->conversion_end_date;
	}
	
	/**
	 * Sets the conversion_end_date
	 * @var string
	 */
	function setConversionEndDate($arg0) {
		if ($this->getConversionDateRange() == 0) {
			$this->conversion_end_date = $arg0;
		}
		$this->addModifiedColumn("conversion_end_date");
		return $this;
	}
	
	/**
	 * Returns the conversion_date_range
	 * @return integer
	 */
	function getConversionDateRange() {
		if (is_null($this->conversion_date_range)) {
			$this->conversion_date_range = 0;
		}
		return $this->conversion_date_range;
	}
	
	/**
	 * Sets the conversion_date_range
	 * @var integer
	 */
	function setConversionDateRange($arg0) {
		switch ((int)$arg0) {
			case 1: // today
				$this->setConversionStartDate(date('m/d/Y'));
				$this->setConversionEndDate(date('m/d/Y'));
				break;
			case 2: //yesterday
				$this->setConversionStartDate(date('m/d/Y', strtotime('yesterday')));
				$this->setConversionEndDate(date('m/d/Y', strtotime('yesterday')));
				break;
			case 3: //last 2 days
				$this->setConversionStartDate(date('m/d/Y', strtotime('yesterday')));
				$this->setConversionEndDate(date('m/d/Y'));
				break;
			case 4: //last 7 days
				$this->setConversionStartDate(date('m/d/Y', strtotime('today - 1 week')));
				$this->setConversionEndDate(date('m/d/Y'));
				break;
			case 5: //last month
				$this->setConversionStartDate(date('m/d/Y', strtotime('today - 1 month')));
				$this->setConversionEndDate(date('m/d/Y'));
				break;
			case 6: //last 3 months
				$this->setConversionStartDate(date('m/d/Y', strtotime('today - 3 months')));
				$this->setConversionEndDate(date('m/d/Y'));
				break;
		}
		$this->conversion_date_range = $arg0;
		$this->addModifiedColumn("conversion_date_range");
		return $this;
	}
	
	/**
	 * Returns the fulfillment_start_date
	 * @return string
	 */
	function getFulfillmentStartDate() {
		if (is_null($this->fulfillment_start_date)) {
			$this->fulfillment_start_date = "";
		}
		return $this->fulfillment_start_date;
	}
	
	/**
	 * Sets the fulfillment_start_date
	 * @var string
	 */
	function setFulfillmentStartDate($arg0) {
		$this->fulfillment_start_date = $arg0;
		$this->addModifiedColumn("fulfillment_start_date");
		return $this;
	}
	
	/**
	 * Returns the fulfillment_end_date
	 * @return string
	 */
	function getFulfillmentEndDate() {
		if (is_null($this->fulfillment_end_date)) {
			$this->fulfillment_end_date = "";
		}
		return $this->fulfillment_end_date;
	}
	
	/**
	 * Sets the fulfillment_end_date
	 * @var string
	 */
	function setFulfillmentEndDate($arg0) {
		$this->fulfillment_end_date = $arg0;
		$this->addModifiedColumn("fulfillment_end_date");
		return $this;
	}
	
	/**
	 * Queries the leads
	 * @return array
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		// Query by the offer ids
		if (count($this->getOfferIdArray()) > 0) {
			$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.offer._id'] = array('$in' => $this->getOfferIdArray());
		}
		// Query by the campaign ids
		if (count($this->getCampaignIdArray()) > 0) {
			$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.campaign._id'] = array('$in' => $this->getCampaignIdArray());
		}
	
		// Query by the the name, email, or _id
		if (trim($this->getKeywords()) != '') {
			$search_params = array();
			if (\MongoId::isValid(trim($this->getKeywords()))) {
				$criteria['_id'] = new \MongoId($this->getKeywords());
			} else {
				$firstname_data_field = \Flux\DataField::retrieveDataFieldFromKeyName('fn');
				$lastname_data_field = \Flux\DataField::retrieveDataFieldFromKeyName('ln');
				$name_data_field = \Flux\DataField::retrieveDataFieldFromKeyName('name');
				$email_data_field = \Flux\DataField::retrieveDataFieldFromKeyName('em');
				if (strpos($this->getKeywords(), '@') !== false) {
					if ($email_data_field !== false) {
						$search_params[] = array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $email_data_field->getKeyName() =>  new \MongoRegex("/" . $this->getKeywords() . "/i"));
					}
					if ($name_data_field !== false) {
						$search_params[] = array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $name_data_field->getKeyName() =>  new \MongoRegex("/" . $this->getKeywords() . "/i"));
					}
				} else if (strpos($this->getKeywords(), ' ') === false) {
					$search_params[] = array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $firstname_data_field->getKeyName() =>  new \MongoRegex("/" . $this->getKeywords() . "/i"));
					$search_params[] = array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $lastname_data_field->getKeyName() =>  new \MongoRegex("/" . $this->getKeywords() . "/i"));
					if ($name_data_field !== false) {
						$search_params[] = array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $name_data_field->getKeyName() =>  new \MongoRegex("/" . $this->getKeywords() . "/i"));
					}
				} else {
					$firstname = substr($this->getKeywords(), 0, strpos($this->getKeywords(), ' '));
					$lastname = substr($this->getKeywords(), strpos($this->getKeywords(), ' ') + 1);
					$search_params[] = array('$and' => array(
							array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $firstname_data_field->getKeyName() =>  new \MongoRegex("/" . $firstname . "/i")),
							array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $lastname_data_field->getKeyName() =>  new \MongoRegex("/" . $lastname . "/i"))
					));
					if ($name_data_field !== false) {
						$search_params[] = array(\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $name_data_field->getKeyName() =>  new \MongoRegex("/" . $this->getKeywords() . "/i"));
					}
				}
				
				$criteria['$or'] = $search_params;
			}		
		}
		
		// Find required fields that have to exist
		if (count($this->getRequiredFields()) > 0) {
			foreach ($this->getRequiredFields() as $required_field) {
				$data_field = \Flux\DataField::retrieveDataFieldFromKeyName($required_field);
				if (!is_null($data_field)) {
					if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
						$criteria[\Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . $data_field->getKeyName()] = array('$exists' => '1');
					} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
						$criteria[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . $data_field->getKeyName()] = array('$exists' => '1');
					} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
						$criteria[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.data_field.data_field_id'] = $data_field->getId();
					}
				}
			}
		}
		
		// Query by the created time of the _id based on a date range
		if ($this->getCreatedStartDate() != '' && $this->getCreatedEndDate() != '') {			
			$criteria['_id'] = array('$gte' => $this->timeToId(strtotime(date('m/d/Y 00:00:00', strtotime($this->getCreatedStartDate())))), '$lte' => $this->timeToId(strtotime(date('m/d/Y 23:59:59', strtotime($this->getCreatedEndDate())))));
		} else if ($this->getCreatedStartDate() != '' && $this->getCreatedEndDate() == '') {
			$criteria['_id'] = array('$gte' => $this->timeToId(strtotime(date('m/d/Y 00:00:00', strtotime($this->getCreatedStartDate())))));
		} else if ($this->getCreatedStartDate() == '' && $this->getCreatedEndDate() != '') {
			$criteria['_id'] = array('$lte' => $this->timeToId(strtotime(date('m/d/Y 23:59:59', strtotime($this->getCreatedEndDate())))));
		}
	
		// Query by the click time of the events based on a date range
		if ($this->getClickStartDate() != '' && $this->getClickEndDate() != '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME, 't' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00', strtotime($this->getClickStartDate())))), '$lte' => new \MongoDate(strtotime(date('m/d/Y 23:59:59', strtotime($this->getClickEndDate())))))));
		} else if ($this->getClickStartDate() != '' && $this->getClickEndDate() == '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME, 't' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00', strtotime($this->getClickStartDate())))))));
		} else if ($this->getClickStartDate() == '' && $this->getClickEndDate() != '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME, 't' => array('$lte' => new \MongoDate(strtotime(date('m/d/Y 23:59:59', strtotime($this->getClickEndDate())))))));
		}
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Searching by conversion date range: " . $this->getConversionEndDate());
		// Query by the conversion time of the events based on a date range
		if ($this->getConversionStartDate() != '' && $this->getConversionEndDate() != '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME, 't' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00', strtotime($this->getConversionStartDate())))), '$lte' => new \MongoDate(strtotime(date('m/d/Y 23:59:59', strtotime($this->getConversionEndDate())))))));
		} else if ($this->getConversionStartDate() != '' && $this->getConversionEndDate() == '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME, 't' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00', strtotime($this->getConversionStartDate())))))));
		} else if ($this->getConversionStartDate() == '' && $this->getConversionEndDate() != '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME, 't' => array('$lte' => new \MongoDate(strtotime(date('m/d/Y 23:59:59', strtotime($this->getConversionEndDate())))))));
		}
		
		// Query by the fulfillment time of the events based on a date range
		if ($this->getFulfillmentStartDate() != '' && $this->getFulfillmentEndDate() != '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 't' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00', strtotime($this->getFulfillmentStartDate())))), '$lte' => new \MongoDate(strtotime(date('m/d/Y 23:59:59', strtotime($this->getFulfillmentEndDate())))))));
		} else if ($this->getFulfillmentStartDate() != '' && $this->getFulfillmentEndDate() == '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 't' => array('$gte' => new \MongoDate(strtotime(date('m/d/Y 00:00:00', strtotime($this->getFulfillmentStartDate())))))));
		} else if ($this->getFulfillmentStartDate() == '' && $this->getFulfillmentEndDate() != '') {
			$criteria['_e'] = array('$elemMatch' => array('data_field.data_field_key_name' => \Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME, 't' => array('$lte' => new \MongoDate(strtotime(date('m/d/Y 23:59:59', strtotime($this->getFulfillmentEndDate())))))));
		}
		
		$ops = json_encode($criteria);
		$ops = str_replace('"$or"', '$or', $ops);
		$ops = str_replace('"$exists"', '$exists', $ops);
		$ops = str_replace('"$in"', '$in', $ops);
		$ops = str_replace('"$elemMatch"', '$elemMatch', $ops);
		$ops = str_replace('"$gte"', '$gte', $ops);
		$ops = str_replace('"$lte"', '$lte', $ops);
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $ops);
		$results = parent::queryAll($criteria, $hydrate, $fields);
		return $results;
	}
	
	/**
	 * Converts a timestamp into a MongoId
	 * @param integer $ts
	 * @return \Flux\MongoId
	 */
	private function timeToId($ts) {
		// turn it into hex
		$hexTs = dechex($ts);
		// pad it out to 8 chars
		$hexTs = str_pad($hexTs, 8, "0", STR_PAD_LEFT);
		// make an _id from it
		return new \MongoId($hexTs."0000000000000000");
	}
	
}