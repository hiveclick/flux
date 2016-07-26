<?php
namespace FluxFE;

use Mojavi\Util\Ajax;

class Offer extends \Flux\Offer {
	
	/**
	 * Returns the cache filename
	 * @return string
	 */
	function getCacheFilename($cache_key) {
		return 'offer_' . $cache_key . '.json';
	}
	
	/**
	 * Returns the cache filename
	 * @return string
	 */
	function getCacheFilenames() {
		$ret_val = array();
		$ret_val[] = $this->getCacheFilename($this->getFolderName());
		$ret_val[] = $this->getCacheFilename($this->getId());
		return $ret_val;
	}

	/**
	 * Queries for an offer
	 * @see \Mojavi\Form\MongoForm::query()
	 */
	function query(array $criteria = array(), $merge_id = true) {
		$url = '/offer/offer';
		$params = array('_id' => $this->getId());
		
		if (defined('FLOW_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(FLOW_CACHE_DIR . '/' . $this->getCacheFilename($this->getId()), $url, $params);
		} else if (defined('MO_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(MO_CACHE_DIR . '/' . $this->getCacheFilename($this->getId()), $url, $params);
		} else {
			$response = Ajax::sendAjax('/offer/offer', array('_id' => $this->getId()));
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
	function queryByFolderName() {
		$url = '/offer/offer';
		$params = array('folder_name' => $this->getFolderName());
		
		if (defined('FLOW_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(FLOW_CACHE_DIR . '/' . $this->getCacheFilename($this->getFolderName()), $url, $params);
		} else if (defined('MO_CACHE_DIR')) {
			$response = Ajax::sendAjaxAndCache(MO_CACHE_DIR . '/' . $this->getCacheFilename($this->getFolderName()), $url, $params);
		} else {
			$response = Ajax::sendAjax($url, $params);
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
		throw new \Exception('Offer::queryAll is not supported on the frontend');
	}

	/**
	 * Inserts a new offer
	 * @see \Mojavi\Form\MongoForm::insert()
	 */
	function insert() {
		throw new \Exception('Offer::insert is not supported on the frontend');
	}

	/**
	 * Updates an existing offer
	 * @see \Mojavi\Form\MongoForm::update()
	 */
	function update($criteria_array = array(), $update_array = array(), $options_array = array(), $use_set_notation = false) {
		throw new \Exception('Offer::update is not supported on the frontend');
	}

	/**
	 * Deletes an existing offer
	 * @see \Mojavi\Form\MongoForm::delete()
	 */
	function delete() {
		throw new \Exception('Offer::delete is not supported on the frontend');
	}
}