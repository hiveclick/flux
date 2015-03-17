<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Zip extends MongoForm {
	
	protected $c;
	protected $z;
	protected $s;
	protected $p;
	protected $s_s;
	protected $co;
	protected $co_s;
	protected $com;
	protected $com_s;
	
	protected $latitude;
	protected $longitude;
	protected $accuracy;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('zip');
		$this->setDbName('util');
	}
	
	/**
	 * Returns the country code
	 * @return string
	 */
	function getCountryCode() {
		return $this->getC();
	}
	
	/**
	 * Sets the country code
	 * @var string
	 */
	function setCountryCode($arg0) {
		return $this->setC($arg0);
	}
	
	/**
	 * Returns the postal code
	 * @return string
	 */
	function getPostalCode() {
		return $this->getZ();
	}
	
	/**
	 * Sets the country code
	 * @var string
	 */
	function setPostalCode($arg0) {
		return $this->setZ($arg0);
	}
	
	/**
	 * Returns the place name (city)
	 * @return string
	 */
	function getPlaceName() {
		return $this->getP();
	}
	
	/**
	 * Sets the place name (city)
	 * @var string
	 */
	function setPlaceName($arg0) {
		return $this->setP($arg0);
	}
	
	/**
	 * Returns the state name (state)
	 * @return string
	 */
	function getStateName() {
		return $this->getS();
	}
	
	/**
	 * Sets the state name (state)
	 * @var string
	 */
	function setStateName($arg0) {
		return $this->setS($arg0);
	}
	
	/**
	 * Returns the state abbreviation (state)
	 * @return string
	 */
	function getState() {
		return $this->getSS();
	}
	
	/**
	 * Sets the state abbreviation (state)
	 * @var string
	 */
	function setState($arg0) {
		return $this->setSS($arg0);
	}
	
	/**
	 * Returns the county name
	 * @return string
	 */
	function getCountyName() {
		return $this->getCo();
	}
	
	/**
	 * Sets the county name
	 * @var string
	 */
	function setCountyName($arg0) {
		return $this->setCo($arg0);
	}
	
	/**
	 * Returns the county abbreviation
	 * @return string
	 */
	function getCounty() {
		return $this->getCoS();
	}
	
	/**
	 * Sets the county abbreviation
	 * @var string
	 */
	function setCounty($arg0) {
		return $this->setCoS($arg0);
	}
	
	/**
	 * Returns the community name
	 * @return string
	 */
	function getCommunityName() {
		return $this->getCom();
	}
	
	/**
	 * Sets the community name
	 * @var string
	 */
	function setCommunityName($arg0) {
		return $this->setCom($arg0);
	}
	
	/**
	 * Returns the community abbreviation
	 * @return string
	 */
	function getCommunity() {
		return $this->getComS();
	}
	
	/**
	 * Sets the community abbreviation
	 * @var string
	 */
	function setCommunity($arg0) {
		return $this->setComS($arg0);
	}
	
	
	/**
	 * Returns the c (country code)
	 * @return string
	 */
	function getC() {
		if (is_null($this->c)) {
			$this->c = "";
		}
		return $this->c;
	}
	
	/**
	 * Sets the c (country code)
	 * @var string
	 */
	function setC($arg0) {
		$this->c = $arg0;
		$this->addModifiedColumn('c');
		return $this;
	}
	
	/**
	 * Returns the z (zip code)
	 * @return string
	 */
	function getZ() {
		if (is_null($this->z)) {
			$this->z = "";
		}
		return $this->z;
	}
	
	/**
	 * Sets the z (zip code)
	 * @var string
	 */
	function setZ($arg0) {
		$this->z = $arg0;
		$this->addModifiedColumn('z');
		return $this;
	}
	
	/**
	 * Returns the p (place name)
	 * @return string
	 */
	function getP() {
		if (is_null($this->p)) {
			$this->p = "";
		}
		return $this->p;
	}
	
	/**
	 * Sets the p (place name)
	 * @var string
	 */
	function setP($arg0) {
		$this->p = $arg0;
		$this->addModifiedColumn('p');
		return $this;
	}
	
	/**
	 * Returns the s (state name)
	 * @return string
	 */
	function getS() {
		if (is_null($this->s)) {
			$this->s = "";
		}
		return $this->s;
	}
	
	/**
	 * Sets the s (state name)
	 * @var string
	 */
	function setS($arg0) {
		$this->s = $arg0;
		$this->addModifiedColumn('s');
		return $this;
	}
	
	/**
	 * Returns the s_s (state abbreviation)
	 * @return string
	 */
	function getSS() {
		if (is_null($this->s_s)) {
			$this->s_s = "";
		}
		return $this->s_s;
	}
	
	/**
	 * Sets the s_s (state abbreviation)
	 * @var string
	 */
	function setSS($arg0) {
		$this->s_s = $arg0;
		$this->addModifiedColumn('ss');
		return $this;
	}
	
	/**
	 * Returns the co (county name)
	 * @return string
	 */
	function getCo() {
		if (is_null($this->co)) {
			$this->co = "";
		}
		return $this->co;
	}
	
	/**
	 * Sets the co (county name)
	 * @var string
	 */
	function setCo($arg0) {
		$this->co = $arg0;
		$this->addModifiedColumn('co');
		return $this;
	}
	
	/**
	 * Returns the co_s (county abbreviation)
	 * @return string
	 */
	function getCoS() {
		if (is_null($this->co_s)) {
			$this->co_s = "";
		}
		return $this->co_s;
	}
	
	/**
	 * Sets the co_s (county abbreviation)
	 * @var string
	 */
	function setCoS($arg0) {
		$this->co_s = $arg0;
		$this->addModifiedColumn('co_s');
		return $this;
	}
	
	/**
	 * Returns the com (community name)
	 * @return string
	 */
	function getCom() {
		if (is_null($this->com)) {
			$this->com = "";
		}
		return $this->com;
	}
	
	/**
	 * Sets the com (community name)
	 * @var string
	 */
	function setCom($arg0) {
		$this->com = $arg0;
		$this->addModifiedColumn('com');
		return $this;
	}
	
	/**
	 * Returns the com_s (community abbreviation)
	 * @return string
	 */
	function getComS() {
		if (is_null($this->com_s)) {
			$this->com_s = "";
		}
		return $this->com_s;
	}
	
	/**
	 * Sets the com_s (community abbreviation)
	 * @var string
	 */
	function setComS($arg0) {
		$this->com_s = $arg0;
		$this->addModifiedColumn('com_s');
		return $this;
	}
	
	/**
	 * Returns the latitude
	 * @return float
	 */
	function getLatitude() {
		if (is_null($this->latitude)) {
			$this->latitude = 0.00;
		}
		return $this->latitude;
	}
	
	/**
	 * Sets the latitude
	 * @var float
	 */
	function setLatitude($arg0) {
		$this->latitude = (float)$arg0;
		$this->addModifiedColumn('latitude');
		return $this;
	}
	
	/**
	 * Returns the longitude
	 * @return float
	 */
	function getLongitude() {
		if (is_null($this->longitude)) {
			$this->longitude = 0.00;
		}
		return $this->longitude;
	}
	
	/**
	 * Sets the longitude
	 * @var float
	 */
	function setLongitude($arg0) {
		$this->longitude = (float)$arg0;
		$this->addModifiedColumn('longitude');
		return $this;
	}
	
	/**
	 * Returns the accuracy
	 * @return float
	 */
	function getAccuracy() {
		if (is_null($this->accuracy)) {
			$this->accuracy = 0.00;
		}
		return $this->accuracy;
	}
	
	/**
	 * Sets the accuracy
	 * @var float
	 */
	function setAccuracy($arg0) {
		$this->accuracy = (float)$arg0;
		$this->addModifiedColumn('accuracy');
		return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$zip = new self();
		$zip->getCollection()->ensureIndex(array('z' => 1));
		return true;
	}
}
