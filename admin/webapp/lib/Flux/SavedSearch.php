<?php
namespace Flux;

class SavedSearch extends Base\SavedSearch {
	
	/**
	 * Returns a parameter from the query string
	 * @return string|array
	 */
	function getQueryStringParam($key, $default = '') {
		$qs = $this->getQueryString();
		if (isset($qs[$key])) {
			return $qs[$key];
		}
		return $default;
	}
	
	/**
	 * Queries all the records
	 * @return array
	 */
	function queryAll(array $criteria = array(), array $fields = array(), $hydrate = true, $timeout = 30000) {
		if ($this->getSearchType() > 0) {
			$criteria['search_type'] = $this->getSearchType();
		}
		if ($this->getName() != '') {
			$criteria['name'] = new \MongoRegex('/' . $this->getName() . '/i');
		}
		if (\MongoId::isValid($this->getUser()->getUserId())) {
			$criteria['$or'] = array(array('user._id' => $this->getUser()->getUserId()), array('is_global' => true));
		}
		return parent::queryAll($criteria, $fields, $hydrate, $timeout);
	}
	
}