<?php
namespace FluxFE;

use Mojavi\Util\Ajax;

class Campaign extends \Flux\Campaign {

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

	/**
	 * Returns the cache filename
	 * @return string
	 */
	function getCacheFilename($cache_key) {
		if (is_string($cache_key)) {
			return 'campaign_' . $cache_key . '.json';
		} else if ($cache_key instanceof \MongoId) {
			return 'campaign_' . $cache_key->__toString() . '.json';
		}
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
	 * Queries for an offer
	 * @see \Mojavi\Form\MongoForm::query()
	 */
	function query(array $criteria = array(), $merge_id = true) {
		$params = array();
		if ($this->getId() instanceof \MongoId) {
			$params['_id'] = $this->getId()->__toString();
		} else if (is_string($this->getId())) {
			$params['_id'] = $this->getId();
		}
		
		if (defined('FLOW_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(FLOW_CACHE_DIR . '/' . $this->getCacheFilename($this->getId()), '/campaign/campaign', $params);
		} else if (defined('MO_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(MO_CACHE_DIR . '/' . $this->getCacheFilename($this->getId()), '/campaign/campaign', $params);
		} else {
			$response = Ajax::sendAjax('/campaign/campaign', $params);
		}
		if (isset($response['record'])) {
			$this->populate($response['record']);
		}
		return $this;
	}

	/**
	 * Queries for an offer
	 * @see \Mojavi\Form\MongoForm::query()
	 */
	function queryAll(array $criteria = array(), array $fields = array(), $hydrate = true, $timeout = 30000) {
		throw new \Exception('Campaign::queryAll is not supported on the frontend');
	}

	/**
	 * Inserts a new offer
	 * @see \Mojavi\Form\MongoForm::insert()
	 */
	function insert() {
		throw new \Exception('Campaign::insert is not supported on the frontend');
	}

	/**
	 * Updates an existing offer
	 * @see \Mojavi\Form\MongoForm::update()
	 */
	function update($criteria_array = array(), $update_array = array(), $options_array = array(), $use_set_notation = false) {
		throw new \Exception('Campaign::update is not supported on the frontend');
	}

	/**
	 * Deletes an existing offer
	 * @see \Mojavi\Form\MongoForm::delete()
	 */
	function delete() {
		throw new \Exception('Campaign::delete is not supported on the frontend');
	}

}