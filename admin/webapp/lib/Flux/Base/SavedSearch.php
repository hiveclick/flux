<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class SavedSearch extends MongoForm {
    
    const SAVED_SEARCH_TYPE_LEAD = 1;
    const SAVED_SEARCH_TYPE_OFFER = 2;
    const SAVED_SEARCH_TYPE_CAMPAIGN = 3;
    
    protected $user;
    protected $search_type;
    protected $name;
    protected $query_string;
    protected $is_global;
    
    /**
     * Constructs new saved search
     * @return void
     */
    function __construct() {
        $this->setCollectionName('saved_search');
        $this->setDbName('admin');
    }
    
    /**
     * Returns the search_type
     * @return integer
     */
    function getSearchType() {
        if (is_null($this->search_type)) {
            $this->search_type = 0;
        }
        return $this->search_type;
    }
    
    /**
     * Sets the search_type
     * @var integer
     */
    function setSearchType($arg0) {
        $this->search_type = (int)$arg0;
        $this->addModifiedColumn("search_type");
        return $this;
    }
    
    /**
     * Returns the name
     * @return string
     */
    function getName() {
        if (is_null($this->name)) {
            $this->name = "";
        }
        return $this->name;
    }
    
    /**
     * Sets the name
     * @var string
     */
    function setName($arg0) {
        $this->name = $arg0;
        $this->addModifiedColumn("name");
        return $this;
    }
    
    /**
     * Returns the user
     * @return \Flux\Link\User
     */
    function getUser() {
        if (is_null($this->user)) {
            $this->user = new \Flux\Link\User();
        }
        return $this->user;
    }
    
    /**
     * Sets the user
     * @var \Flux\Link\User
     */
    function setUser($arg0) {
        if (is_array($arg0)) {
            $this->user = new \Flux\Link\User();
            $this->user->populate($arg0);
            if (\MongoId::isValid($this->user->getUserId()) && $this->user->getUsername() == '') {
                $this->user->setUsername($this->user->getUser()->getName());
            }
            $this->addModifiedColumn("user");
        } else if (is_string($arg0)) {
            $this->user = new \Flux\Link\User();
            $this->user->setUserId($arg0);
            if (\MongoId::isValid($this->user->getUserId()) && $this->user->getUsername() == '') {
                $this->user->setUsername($this->user->getUser()->getName());
            }
            $this->addModifiedColumn("user");
        } else if ($arg0 instanceof \MongoId) {
            $this->user = new \Flux\Link\User();
            $this->user->setUserId($arg0);
            if (\MongoId::isValid($this->user->getUserId()) && $this->user->getUsername() == '') {
                $this->user->setUsername($this->user->getUser()->getName());
            }
            $this->addModifiedColumn("user");
        }
        return $this;
    }
    
    /**
     * Returns the query_string
     * @return array
     */
    function getQueryString() {
        if (is_null($this->query_string)) {
            $this->query_string = array();
        }
        return $this->query_string;
    }
    
    /**
     * Sets the query_string
     * @var array
     */
    function setQueryString($arg0) {
        if (is_array($arg0)) {
            $this->query_string = $arg0;
            $this->addModifiedColumn("query_string");
        } else if (is_string($arg0)) {
            $params = array();
            parse_str($arg0, $params);
            $this->query_string = $params;
            $this->addModifiedColumn("query_string");
        }
        return $this;
    }
    
    /**
     * Returns the is_global
     * @return boolean
     */
    function getIsGlobal() {
        if (is_null($this->is_global)) {
            $this->is_global = false;
        }
        return $this->is_global;
    }
    
    /**
     * Sets the is_global
     * @var boolean
     */
    function setIsGlobal($arg0) {
        $this->is_global = (boolean)$arg0;
        $this->addModifiedColumn("is_global");
        return $this;
    }
    
    
}