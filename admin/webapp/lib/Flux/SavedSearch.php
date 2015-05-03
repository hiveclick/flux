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
    function queryAll(array $criteria = array(), $hydrate = true) {
        if ($this->getSearchType() > 0) {
            $criteria['search_type'] = $this->getSearchType();
        }
        if ($this->getName() != '') {
            $criteria['name'] = new \MongoRegex('/' . $this->getName() . '/i');
        }
        if ($this->getUser()->getUserId() > 0) {
            $criteria['$or'] = array(array('user.user_id' => $this->getUser()->getUserId()), array('is_global' => true));
        }
        return parent::queryAll($criteria, $hydrate);
    }
    
}