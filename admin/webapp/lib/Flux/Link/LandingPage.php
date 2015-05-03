<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class LandingPage extends CommonForm {
    
    protected $name;
    protected $url;
    
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
}