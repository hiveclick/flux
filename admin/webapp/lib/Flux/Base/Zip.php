<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Zip extends MongoForm {
	
	protected $country;
	protected $zipcode;
	protected $city;
	protected $state;
	protected $state_abbreviation;
	protected $county;
	protected $county_abbreviation;
	protected $community;
	protected $community_abbreviation;
	protected $latitude;
	protected $longitude;
	protected $accuracy;
		
	/**
	 * Constructs new zip
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('zip');
		$this->setDbName('admin');
		$this->setIdType(self::ID_TYPE_MONGO);
	}
	
	/**
	 * Returns the country
	 * @return string
	 */
	function getCountry() {
		if (is_null($this->country)) {
			$this->country = "";
		}
		return $this->country;
	}
	
	/**
	 * Sets the country
	 * @var string
	 */
	function setCountry($arg0) {
		$this->country = $arg0;
		$this->addModifiedColumn("country");
		return $this;
	}
	
	/**
	 * Returns the zipcode
	 * @return string
	 */
	function getZipcode() {
		if (is_null($this->zipcode)) {
			$this->zipcode = "";
		}
		return $this->zipcode;
	}
	
	/**
	 * Sets the zipcode
	 * @var string
	 */
	function setZipcode($arg0) {
		$this->zipcode = $arg0;
		$this->addModifiedColumn("zipcode");
		return $this;
	}
	
	/**
	 * Returns the zipcode_type
	 * @return string
	 */
	function getZipcodeType() {
		if (is_null($this->zipcode_type)) {
			$this->zipcode_type = "";
		}
		return $this->zipcode_type;
	}
	
	/**
	 * Sets the zipcode_type
	 * @var string
	 */
	function setZipcodeType($arg0) {
		$this->zipcode_type = $arg0;
		$this->addModifiedColumn("zipcode_type");
		return $this;
	}
	
	/**
	 * Returns the city
	 * @return string
	 */
	function getCity() {
		if (is_null($this->city)) {
			$this->city = "";
		}
		return $this->city;
	}
	
	/**
	 * Sets the city
	 * @var string
	 */
	function setCity($arg0) {
		$this->city = $arg0;
		$this->addModifiedColumn("city");
		return $this;
	}
	
	/**
	 * Returns the state
	 * @return string
	 */
	function getState() {
		if (is_null($this->state)) {
			$this->state = "";
		}
		return $this->state;
	}
	
	/**
	 * Sets the state
	 * @var string
	 */
	function setState($arg0) {
		$this->state = $arg0;
		$this->addModifiedColumn("state");
		return $this;
	}
	
	/**
	 * Returns the state_abbreviation
	 * @return string
	 */
	function getStateAbbreviation() {
		if (is_null($this->state_abbreviation)) {
			$this->state_abbreviation = "";
		}
		return $this->state_abbreviation;
	}
	
	/**
	 * Sets the state_abbreviation
	 * @var string
	 */
	function setStateAbbreviation($arg0) {
		$this->state_abbreviation = $arg0;
		$this->addModifiedColumn("state_abbreviation");
		return $this;
	}
	
	/**
	 * Returns the county
	 * @return string
	 */
	function getCounty() {
		if (is_null($this->county)) {
			$this->county = "";
		}
		return $this->county;
	}
	
	/**
	 * Sets the county
	 * @var string
	 */
	function setCounty($arg0) {
		$this->county = $arg0;
		$this->addModifiedColumn("county");
		return $this;
	}
	
	/**
	 * Returns the county_abbreviation
	 * @return string
	 */
	function getCountyAbbreviation() {
		if (is_null($this->county_abbreviation)) {
			$this->county_abbreviation = "";
		}
		return $this->county_abbreviation;
	}
	
	/**
	 * Sets the county_abbreviation
	 * @var string
	 */
	function setCountyAbbreviation($arg0) {
		$this->county_abbreviation = $arg0;
		$this->addModifiedColumn("county_abbreviation");
		return $this;
	}
	
	/**
	 * Returns the community
	 * @return string
	 */
	function getCommunity() {
		if (is_null($this->community)) {
			$this->community = "";
		}
		return $this->community;
	}
	
	/**
	 * Sets the community
	 * @var string
	 */
	function setCommunity($arg0) {
		$this->community = $arg0;
		$this->addModifiedColumn("community");
		return $this;
	}
	
	/**
	 * Returns the community_abbreviation
	 * @return string
	 */
	function getCommunityAbbreviation() {
		if (is_null($this->community_abbreviation)) {
			$this->community_abbreviation = "";
		}
		return $this->community_abbreviation;
	}
	
	/**
	 * Sets the community_abbreviation
	 * @var string
	 */
	function setCommunityAbbreviation($arg0) {
		$this->community_abbreviation = $arg0;
		$this->addModifiedColumn("community_abbreviation");
		return $this;
	}
	
	/**
	 * Returns the latitude
	 * @return float
	 */
	function getLatitude() {
		if (is_null($this->latitude)) {
			$this->latitude = 0.0;
		}
		return $this->latitude;
	}
	
	/**
	 * Sets the latitude
	 * @var float
	 */
	function setLatitude($arg0) {
		$this->latitude = (float)$arg0;
		$this->addModifiedColumn("latitude");
		return $this;
	}
	
	/**
	 * Returns the longitude
	 * @return float
	 */
	function getLongitude() {
		if (is_null($this->longitude)) {
			$this->longitude = 0.0;
		}
		return $this->longitude;
	}
	
	/**
	 * Sets the longitude
	 * @var float
	 */
	function setLongitude($arg0) {
		$this->longitude = (float)$arg0;
		$this->addModifiedColumn("longitude");
		return $this;
	}
	
	/**
	 * Returns the accuracy
	 * @return integer
	 */
	function getAccuracy() {
		if (is_null($this->accuracy)) {
			$this->accuracy = 1;
		}
		return $this->accuracy;
	}
	
	/**
	 * Sets the accuracy
	 * @var integer
	 */
	function setAccuracy($arg0) {
		$this->accuracy = (int)$arg0;
		$this->addModifiedColumn("accuracy");
		return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$zip = new self();
		$zip->getCollection()->ensureIndex(array('zipcode' => 1), array('background' => true, 'unique' => true));
		return true;
	}
}
