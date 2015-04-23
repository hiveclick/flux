<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class TrafficSource extends MongoForm {
    
    protected $name;
    protected $description;
    protected $url;
    protected $username;
    protected $password;
    protected $icon;
    
    /**
     * Constructs new user
     * @return void
     */
    function __construct() {
        $this->setCollectionName('traffic_source');
        $this->setDbName('admin');
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
     * Returns the description
     * @return string
     */
    function getDescription() {
        if (is_null($this->description)) {
            $this->description = "";
        }
        return $this->description;
    }
    
    /**
     * Sets the description
     * @var string
     */
    function setDescription($arg0) {
        $this->description = $arg0;
        $this->addModifiedColumn("description");
        return $this;
    }
    
    /**
     * Returns the url
     * @return string
     */
    function getUrl() {
        if (is_null($this->url)) {
            $this->url = "";
        }
        return $this->url;
    }
    
    /**
     * Sets the url
     * @var string
     */
    function setUrl($arg0) {
        $this->url = $arg0;
        $this->addModifiedColumn("url");
        return $this;
    }
    
    /**
     * Returns the username
     * @return string
     */
    function getUsername() {
        if (is_null($this->username)) {
            $this->username = "";
        }
        return $this->username;
    }
    
    /**
     * Sets the username
     * @var string
     */
    function setUsername($arg0) {
        $this->username = $arg0;
        $this->addModifiedColumn("username");
        return $this;
    }
    
    /**
     * Returns the password
     * @return string
     */
    function getPassword() {
        if (is_null($this->password)) {
            $this->password = "";
        }
        return $this->password;
    }
    
    /**
     * Sets the password
     * @var string
     */
    function setPassword($arg0) {
        $this->password = $arg0;
        $this->addModifiedColumn("password");
        return $this;
    }
    
    /**
     * Returns the icon
     * @return string
     */
    function getIcon() {
        if (is_null($this->icon)) {
            $this->icon = "";
        }
        return $this->icon;
    }
    
    /**
     * Sets the icon
     * @var string
     */
    function setIcon($arg0) {
        $this->icon = $arg0;
        $this->addModifiedColumn("icon");
        return $this;
    }
}