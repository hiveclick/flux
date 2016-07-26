<?php
namespace FluxFE;

use Mojavi\Util\Ajax;

class OfferPage extends \Flux\OfferPage {

	/**
	 * Returns the cache filename
	 * @return string
	 */
	function getCacheFilename($cache_key) {
		return 'offer_' . $this->getOffer()->getOfferId() . '_page_' . $cache_key . '.json';
	}
	
	/**
	 * Returns the cache filename
	 * @return string
	 */
	function getCacheFilenames() {
		$ret_val = array();
		$ret_val[] = $this->getCacheFilename(md5($this->getPageName()));
		$ret_val[] = $this->getCacheFilename($this->getId());
		return $ret_val;
	}
	
	/**
	 * Returns the next page in the flow
	 * @return OfferPage
	 */
	function getNextPage() {
		$page_found = false;
		foreach ($this->queryAll() as $offer_page) {
			if ($page_found) {
				return $offer_page;
			}
			if ($offer_page->getId() == $this->getId()) {
				$page_found = true;
			}
		}
		return $this;
	}

	/**
	 * Queries for an offer
	 * @see \Mojavi\Form\MongoForm::query()
	 */
	function query(array $criteria = array(), $merge_id = true) {
		if (defined('FLOW_CACHE_DIR')) {
			$response = \Mojavi\Util\Ajax::sendAjaxAndCache(FLOW_CACHE_DIR . '/' . $this->getCacheFilename($this->getId()), '/offer/offer-page', array('_id' => $this->getId()));
		} else {
			$response = \Mojavi\Util\Ajax::sendAjax('/offer/offer-page', array('_id' => $this->getId()));
		}
		if (isset($response['record'])) {
			$this->populate($response['record']);
		}
		return $this;
	}

	/**
	 * Queries for an offer_page
	 * @see \Mojavi\Form\MongoForm::query()
	 */
	function queryByPageName() {
		if (defined('FLOW_CACHE_DIR')) {
			$response = \Mojavi\Util\Ajax::sendAjaxAndCache(FLOW_CACHE_DIR . '/' . $this->getCacheFilename(md5($this->getPageName())), '/offer/offer-page', array('file_path' => $this->getFilePath(), 'preview_url' => $this->getPreviewUrl(), 'offer_id' => $this->getOffer()->getOfferId(), 'page_name' => $this->getPageName()));
		} else {
			$response = \Mojavi\Util\Ajax::sendAjax('/offer/offer-page', array('file_path' => $this->getFilePath(), 'preview_url' => $this->getPreviewUrl(), 'offer_id' => $this->getOffer()->getOfferId(), 'page_name' => $this->getPageName()));
		}
		
		if (isset($response['record'])) {
			$this->populate($response['record']);
		}
		return $this;
	}

	/**
	 * Queries for an offer_page
	 * @see \Mojavi\Form\MongoForm::query()
	 */
	function queryAll(array $criteria = array(), array $fields = array(), $hydrate = true, $timeout = 30000) {
		$ret_val = array();
		if (defined('FLOW_CACHE_DIR')) {
			$response = \Mojavi\Util\Ajax::sendAjaxAndCache(FLOW_CACHE_DIR . '/offer_' . $this->getOffer()->getOfferId() . '_pages.json', '/offer/offer-page', array('offer_id' => $this->getOffer()->getOfferId(), 'sort' => 'priority', 'sord' => 'ASC'));
		} else {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "FLOW_CACHE_DIR is not defined");
			$response = Ajax::sendAjax('/offer/offer-page', array('offer_id' => $this->getOffer()->getOfferId(), 'sort' => 'priority', 'sord' => 'ASC'));
		}
		
		if (isset($response['entries'])) {
			foreach ($response['entries'] as $entry) {
				$offer_page = new \FluxFE\OfferPage();
				$offer_page->populate($entry);
				$ret_val[] = $offer_page;
			}
		}
		return $ret_val;
	}

	/**
	 * Inserts a new offer_page
	 * @see \Mojavi\Form\MongoForm::insert()
	 */
	function insert() {
		throw new \Exception('OfferPage::insert is not supported on the frontend');
	}

	/**
	 * Updates an existing offer_page
	 * @see \Mojavi\Form\MongoForm::update()
	 */
	function update($criteria_array = array(), $update_array = array(), $options_array = array(), $use_set_notation = false) {
		throw new \Exception('OfferPage::update is not supported on the frontend');
	}

	/**
	 * Deletes an existing offer_page
	 * @see \Mojavi\Form\MongoForm::delete()
	 */
	function delete() {
		throw new \Exception('OfferPage::delete is not supported on the frontend');
	}
}