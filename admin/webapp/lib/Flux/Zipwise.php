<?php
namespace Flux;

use \Mojavi\Form\CommonForm;
/**
 * Uses the zipwise api to find city and state from the zipcode
 * @author Mark Hobson
 */
class Zipwise extends CommonForm {
	
	const API_KEY = 'ocx9xvbfmw8nu7bj';
	
	private $zipcode;
	private $city;
	private $state;
	
	/**
	 * Constructs a new zipwise object
	 * @param $zipcode
	 */
	function __construct($zipcode) {
		$this->setZipcode($zipcode);
	}
	
	/**
	 * Returns the city
	 * @return string
	 */
	function getCity() {
		if (is_null($this->city)) {
			if ($this->getZipcode() != '') {
				$this->lookup();
			} else {
				$this->city = "";
			}
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
			if ($this->getZipcode() != '') {
				$this->lookup();
			} else {
				$this->state = "";
			}
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
	 * Does the zip lookup
	 * @return boolean
	 */
	private function lookup() {
		$response_obj = \Mojavi\Util\Ajax::sendAjax('/webservices/zipinfo.php', array('key' => self::API_KEY, 'zip' => $this->getZipcode(), 'format' => 'json'), \Mojavi\Request\Request::GET, 'https://www.zipwise.com/');
		if (isset($response_obj['results'])) {
			if (isset($response_obj['results']['cities']) && is_array($response_obj['results']['cities'])) {
				$primary_city = array_shift($response_obj['results']['cities']);
				$this->setCity($primary_city['city']);
			}
			if (isset($response_obj['results']['state'])) {
				$this->setState($response_obj['results']['state']);
			}
		}
	}
	
}