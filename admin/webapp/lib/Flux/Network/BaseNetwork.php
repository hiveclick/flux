<?php
namespace Flux\Network;

use Mojavi\Form\CommonForm;

class BaseNetwork extends CommonForm {
    
    private $api_token;
    private $api_url;
    private $client;
    private $network_username;
    private $network_password;
    private $start_date;
    private $end_date;
    
    /**
     * Returns the api_token
     * @return string
     */
    function getApiToken() {
        if (is_null($this->api_token)) {
            $this->api_token = "";
        }
        return $this->api_token;
    }
    
    /**
     * Sets the api_token
     * @var string
     */
    function setApiToken($arg0) {
        $this->api_token = $arg0;
        $this->addModifiedColumn("api_token");
        return $this;
    }
    
    /**
     * Returns the api_url
     * @return string
     */
    function getApiUrl() {
        if (is_null($this->api_url)) {
            $this->api_url = "";
        }
        return $this->api_url;
    }
    
    /**
     * Sets the api_url
     * @var string
     */
    function setApiUrl($arg0) {
        $this->api_url = $arg0;
        $this->addModifiedColumn("api_url");
        return $this;
    }
    
    /**
     * Returns the network_username
     * @return string
     */
    function getNetworkUsername() {
        if (is_null($this->network_username)) {
            $this->network_username = "";
        }
        return $this->network_username;
    }
    
    /**
     * Sets the network_username
     * @var string
     */
    function setNetworkUsername($arg0) {
        $this->network_username = $arg0;
        $this->addModifiedColumn("network_username");
        return $this;
    }
    
    /**
     * Returns the network_password
     * @return string
     */
    function getNetworkPassword() {
        if (is_null($this->network_password)) {
            $this->network_password = "";
        }
        return $this->network_password;
    }
    
    /**
     * Sets the network_password
     * @var string
     */
    function setNetworkPassword($arg0) {
        $this->network_password = $arg0;
        $this->addModifiedColumn("network_password");
        return $this;
    }
    
    /**
     * Returns the start_date
     * @return string
     */
    function getStartDate() {
        if (is_null($this->start_date)) {
            $this->start_date = date("m/d/Y");
        }
        return $this->start_date;
    }
    
    /**
     * Sets the start_date
     * @var string
     */
    function setStartDate($arg0) {
        $this->start_date = $arg0;
        $this->addModifiedColumn("start_date");
        return $this;
    }
    
    /**
     * Returns the end_date
     * @return string
     */
    function getEndDate() {
        if (is_null($this->end_date)) {
            $this->end_date = date("m/d/Y");
        }
        return $this->end_date;
    }
    
    /**
     * Sets the end_date
     * @var string
     */
    function setEndDate($arg0) {
        $this->end_date = $arg0;
        $this->addModifiedColumn("end_date");
        return $this;
    }
    
    /**
     * Returns the client
     * @return \Flux\Link\Client
     */
    function getClient() {
        if (is_null($this->client)) {
            $this->client = new \Flux\Link\Client();
        }
        return $this->client;
    }
    
    /**
     * Sets the client
     * @var \Flux\Link\Client
     */
    function setClient($arg0) {
        if (is_array($arg0)) {
            $this->client = new \Flux\Link\Client();
            $this->client->populate($arg0);
            if ($this->client->getClientId() > 0 && $this->client->getClientName() == '') {
                $this->client->setClientName($this->client->getClient()->getName());
            }
        } else if (is_string($arg0) || is_int($arg0)) {
            $this->client = new \Flux\Link\Client();
            $this->client->setClientId($arg0);
            if ($this->client->getClientId() > 0 && $this->client->getClientName() == '') {
                $this->client->setClientName($this->client->getClient()->getName());
            }
        }
        $this->addModifiedColumn("client");
        return $this;
    }
    
    /**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncNetworkRevenue($start_date, $end_date) {
	    \Mojavi\Util\StringTools::consoleWrite(' - Syncing network revenue', 'Not Setup', \Mojavi\Util\StringTools::CONSOLE_COLOR_YELLOW, true);
	} 
	
	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncOfferRevenue($start_date, $end_date) {
	    \Mojavi\Util\StringTools::consoleWrite(' - Syncing offer revenue', 'Not Setup', \Mojavi\Util\StringTools::CONSOLE_COLOR_YELLOW, true);
	}
	
	/**
	 * Syncs revenue with this network
	 * @return boolean
	 */
	function syncLeadRevenue($start_date, $end_date) {
	    \Mojavi\Util\StringTools::consoleWrite(' - Syncing lead revenue', 'Not Setup', \Mojavi\Util\StringTools::CONSOLE_COLOR_YELLOW, true);
	}
}