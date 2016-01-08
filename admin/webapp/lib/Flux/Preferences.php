<?php
namespace Flux;

class Preferences extends Base\Preferences {
	
	private $preferences_array;
	
	/**
	 * Returns the preferences_array
	 * @return array
	 */
	function getPreferencesArray() {
		if (is_null($this->preferences_array)) {
			$this->preferences_array = array();
		}
		return $this->preferences_array;
	}
	
	/**
	 * Sets the preferences_array
	 * @var array
	 */
	function setPreferencesArray($arg0) {
		$this->preferences_array = $arg0;
		return $this;
	}
	
	/**
	 * Queries by the name
	 * @return string
	 */
	function queryByKey() {
		$criteria = array('key' => $this->getKey());
		return parent::query($criteria, false);
	}
	
	/**
	 * Returns the preference value for a name
	 * @param string $preference_name
	 * @return string
	 */
	static function savePreference($preference_name, $preference_value) {
		/* @var $preference \Flux\Preferences */
		$preference = new \Flux\Preferences();
		$preference->setKey(strtolower($preference_name));
		$preference->setValue($preference_value);
		$insert_id = $preference->update();
		return $insert_id;
	}
	
	/**
	 * Returns the setting value for a name
	 * @param string $preference_name
	 * @return string
	 */
	static function getPreference($preference_name, $default_value = '') {
		/* @var $preference \Flux\Preferences */
		$preference = new \Flux\Preferences();
		$preference->setKey(strtolower($preference_name));
		if (($preference = $preference->queryByKey()) !== false) {
			return $preference->getValue();
		} else {
			return $default_value;
		}
	}
	
	/**
	 * Queries by the name
	 * @return string
	 */
	function insert() {
		return $this->update();
	}
	
	/**
	 * Queries by the name
	 * @return string
	 */
	function update($criteria_array = array(), $update_array = array(), $options_array = array('upsert' => true), $use_set_notation = false) {
		if (empty($this->getPreferencesArray())) {
			// If we don't have multiple values, then just save our setting
			return parent::updateMultiple(array('key' => $this->getKey()), array('$set' => array('value' => $this->getValue())));
		} else {
			// If we are saving multiple values, then do it one at a time
			foreach ($this->getPreferencesArray() as $key => $value) {
				/* @var $preference \Flux\Preferences */
				$preference = new \Flux\Preferences();
				$preference->setKey($key);
				$preference->setValue($value);
				$insert_id = $preference->update();
			}
			return $insert_id;
		}
	}
	
}