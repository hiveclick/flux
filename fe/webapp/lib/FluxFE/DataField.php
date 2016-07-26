<?php
namespace FluxFE;

use Mojavi\Util\Ajax;

class DataField extends \Flux\DataField {

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

	/**
	 * Returns the cache filename
	 * @return string
	 */
	function getCacheFilename($cache_key) {
		return 'datafield_' . $cache_key . '.json';
	}
	
	/**
	 * Returns the cache filename
	 * @return string
	 */
	function getCacheFilenames() {
		$ret_val = array();
		$ret_val[] = $this->getCacheFilename($this->getId());
		return $ret_val;
	}
	
	/**
	 * Queries a data field by it's name
	 * @return Flux\DataField
	 */
	function query(array $criteria = array(), $merge_id = true) {
		$params = array('_id' => $this->getId());
		$url = '/admin/datafield';
		
		if (defined('FLOW_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(FLOW_CACHE_DIR . '/' . $this->getCacheFilename($this->getId()) . '.json', $url, $params);
		} else if (defined('MO_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(MO_CACHE_DIR . '/' . $this->getCacheFilename($this->getId()) . '.json', $url, $params);
		} else {
			$response = Ajax::sendAjax($url, $params);
		}
		if (isset($response['record'])) {
			$this->populate($response['record']);
		}
		return $this;
	}

	/**
	 * Queries a report column by it's name
	 * @return Flux\DataField
	 */
	function queryAll(array $criteria = array(), array $fields = array(), $hydrate = true, $timeout = 30000) {
		$params = array('ignore_pagination' => 1);
		$url = '/admin/datafield';
		$ret_val = array();
		
		if (defined('FLOW_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(FLOW_CACHE_DIR . '/datafields.json', $url, $params);
		} else if (defined('MO_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(MO_CACHE_DIR . '/datafields.json', $url, $params);
		} else {
			$response = Ajax::sendAjax($url, $params);
		}
				
		if (isset($response['entries'])) {
			foreach ($response['entries'] as $entry) {
				$data_field = new \FluxFE\DataField();
				$data_field->populate($entry);
				$ret_val[] = $data_field;
			}
		}
		return $ret_val;
	}
}
?>