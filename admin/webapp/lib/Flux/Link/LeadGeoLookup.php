<?php
namespace Flux\Link;

class LeadGeoLookup extends \Flux\Link\Lead {

    protected $city;
    protected $state;
    protected $postal_code;
    
    /**
     * Returns the city
     * @return string
     */
    function getCity() {
        if (is_null($this->city)) {
            $this->city = "";
        }
        return $this->city;
    }
    
    /**
     * Sets the city
     * @var string
     */
    function setCity($arg0) {
        $this->city = $arg0;
        $this->addModifiedColumn("city");
        return $this;
    }
    
    /**
     * Returns the state
     * @return string
     */
    function getState() {
        if (is_null($this->state)) {
            $this->state = "";
        }
        return $this->state;
    }
    
    /**
     * Sets the state
     * @var string
     */
    function setState($arg0) {
        $this->state = $arg0;
        $this->addModifiedColumn("state");
        return $this;
    }
    
    /**
     * Returns the postal_code
     * @return string
     */
    function getPostalCode() {
        if (is_null($this->postal_code)) {
            $this->postal_code = "";
        }
        return $this->postal_code;
    }
    
    /**
     * Sets the postal_code
     * @var string
     */
    function setPostalCode($arg0) {
        $this->postal_code = $arg0;
        $this->addModifiedColumn("postal_code");
        return $this;
    }
    
    /**
     * Performs the geo ip lookup
     * @return this
     */
    function lookup() {
        if ($this->getPostalCode() != '') {
            $zip = \Flux\Zip::lookup($this->getPostalCode());
            if ($this->getCity() == '') { $this->setCity($zip->getCity()); }
            if ($this->getState() == '') { $this->setState($zip->getStateAbbreviation()); }    
        }
        if ($this->getCity() == '' || $this->getState() == '') {
            if ($this->getLead()->getValue('zi') != '') {
                $zip = \Flux\Zip::lookup($this->getLead()->getValue('zi'));
                if ($this->getCity() == '') { $this->setCity($zip->getCity()); }
                if ($this->getState() == '') { $this->setState($zip->getStateAbbreviation()); }
            }
        }
        if ($this->getCity() == '' || $this->getState() == '') {
            if ($this->getLead()->getValue('ip') != '') {
                $geo_result = geoip_record_by_name($this->getLead()->getValue('ip'));
                if ($this->getCity() == '') { $this->setCity($geo_result['city']); }
                if ($this->getState() == '') { $this->setState($geo_result['region']); }
                if ($this->getPostalCode() == '') { $this->setState($geo_result['postal_code']); }
            }
        }
    }
}
?>