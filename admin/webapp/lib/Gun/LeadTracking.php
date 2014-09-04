<?php
namespace Gun;

use Mojavi\Form\MojaviForm;

class LeadTracking extends MojaviForm {
        
    protected $_ip;
    protected $_qs;
    protected $_ref;
    protected $_url;
    protected $_ua;
    protected $_uab;
    protected $_uav;
    protected $_uap;
    
    protected $s1;
    protected $s2;
    protected $s3;
    protected $s4;
    protected $s5;
    protected $uid;
    
    protected $_c;
    protected $_o;
    protected $_ck;
    
    protected $_campaign_id;
    protected $_client_id;
    protected $_offer_id;
    protected $_client_name;
    protected $_offer_name;
    
    private $_offer;
    private $_campaign;
    private $_client;
    
    function __construct() {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->getUserAgentBrowser();
            $this->getUserAgentVersion();
            $this->getUserAgentPlatform();
        }
    }
    
    /**
     * Returns the _ip
     * @return string
     */
    function getIp() {
    	if (is_null($this->_ip)) {
    	    if (isset($_SERVER['REMOTE_ADDR'])) {
                $this->_ip = $_SERVER['REMOTE_ADDR'];
    	    } else {
    	        $this->_ip = "";
    	    }
    	}
    	return $this->_ip;
    }
    
    /**
     * Sets the _ip
     * @var string
     */
    function setIp($arg0) {
    	$this->_ip = $arg0;
    	$this->addModifiedColumn("_ip");
    	return $this;
    }
    
    /**
     * Returns the qs
     * @return string
     */
    function getQs() {
    	if (is_null($this->_qs)) {
    	    if (isset($_SERVER['QUERY_STRING'])) {
                $this->_qs = $_SERVER['QUERY_STRING'];
    	    } else {
    	        $this->_qs = "";
    	    }
    	}
    	return $this->_qs;
    }
    
    /**
     * Sets the qs
     * @var string
     */
    function setQs($arg0) {
    	$this->_qs = $arg0;
    	$this->addModifiedColumn("_qs");
    	return $this;
    }
    
    /**
     * Returns the _ref
     * @return string
     */
    function getRef() {
    	if (is_null($this->_ref)) {
    	    if (isset($_SERVER['HTTP_REFERER'])) {
                $this->_ref = $_SERVER['HTTP_REFERER'];
    	    } else {
    	        $this->_ref = "";
    	    }
    	}
    	return $this->_ref;
    }
    
    /**
     * Sets the _ref
     * @var string
     */
    function setRef($arg0) {
    	$this->_ref = $arg0;
    	$this->addModifiedColumn("_ref");
    	return $this;
    }
    
    /**
     * Returns the _url
     * @return string
     */
    function getUrl() {
    	if (is_null($this->_url)) {
    	    if (isset($_SERVER['REQUEST_URI'])) {
                $this->_url = $_SERVER['REQUEST_URI'];
    	    } else {
    	        $this->_url = "";
    	    }
    	}
    	return $this->_url;
    }
    
    /**
     * Sets the _url
     * @var string
     */
    function setUrl($arg0) {
    	$this->_url = $arg0;
    	$this->addModifiedColumn("_url");
    	return $this;
    }
    
    /**
     * Returns the _ua
     * @return string
     */
    function getUa() {
    	if (is_null($this->_ua)) {
    	    if (isset($_SERVER['HTTP_USER_AGENT'])) {
    	    	$this->_ua = $_SERVER['HTTP_USER_AGENT'];
    	    } else {
    	    	$this->_ua = "";
    	    }
    	}
    	return $this->_ua;
    }
    
    /**
     * Sets the _ua
     * @var string
     */
    function setUa($arg0) {
    	$this->_ua = $arg0;
    	$this->addModifiedColumn("_ua");
    	return $this;
    }
    
    
    
    /**
     * Returns the _uab
     * @return string
     */
    function getUab() {
    	if (is_null($this->_uab)) {
    		$this->_uab = self::getUserAgentInfo()->getData()->browser;
    	}
    	return $this->_uab;
    }
    
    /**
     * Sets the _uab
     * @var string
     */
    function setUab($arg0) {
    	$this->_uab = $arg0;
    	$this->addModifiedColumn("_uab");
    	return $this;
    }
    
    /**
     * Returns the _uav
     * @return string
     */
    function getUav() {
    	if (is_null($this->_uav)) {
    		$this->_uav = self::getUserAgentInfo()->getData()->version;
    	}
    	return $this->_uav;
    }
    
    /**
     * Sets the _uav
     * @var string
     */
    function setUav($arg0) {
    	$this->_uav = $arg0;
    	$this->addModifiedColumn("_uav");
    	return $this;
    }
    
    /**
     * Returns the _uap
     * @return string
     */
    function getUap() {
    	if (is_null($this->_uap)) {
    		$this->_uap = self::getUserAgentInfo()->getData()->platform;
    	}
    	return $this->_uap;
    }
    
    /**
     * Sets the _uap
     * @var string
     */
    function setUap($arg0) {
    	$this->_uap = $arg0;
    	$this->addModifiedColumn("_uap");
    	return $this;
    }
    
    /**
     * Returns the s1
     * @return string
     */
    function getS1() {
    	if (is_null($this->s1)) {
    		$this->s1 = "";
    	}
    	return $this->s1;
    }
    
    /**
     * Sets the s1
     * @var string
     */
    function setS1($arg0) {
    	$this->s1 = $arg0;
    	$this->addModifiedColumn("s1");
    	return $this;
    }
    
    /**
     * Returns the s2
     * @return string
     */
    function getS2() {
    	if (is_null($this->s2)) {
    		$this->s2 = "";
    	}
    	return $this->s2;
    }
    
    /**
     * Sets the s2
     * @var string
     */
    function setS2($arg0) {
    	$this->s2 = $arg0;
    	$this->addModifiedColumn("s2");
    	return $this;
    }
    
    /**
     * Returns the s3
     * @return string
     */
    function getS3() {
    	if (is_null($this->s3)) {
    		$this->s3 = "";
    	}
    	return $this->s3;
    }
    
    /**
     * Sets the s3
     * @var string
     */
    function setS3($arg0) {
    	$this->s3 = $arg0;
    	$this->addModifiedColumn("s3");
    	return $this;
    }
    
    /**
     * Returns the s4
     * @return string
     */
    function getS4() {
    	if (is_null($this->s4)) {
    		$this->s4 = "";
    	}
    	return $this->s4;
    }
    
    /**
     * Sets the s4
     * @var string
     */
    function setS4($arg0) {
    	$this->s4 = $arg0;
    	$this->addModifiedColumn("s4");
    	return $this;
    }
    
    /**
     * Returns the s5
     * @return string
     */
    function getS5() {
    	if (is_null($this->s5)) {
    		$this->s5 = "";
    	}
    	return $this->s5;
    }
    
    /**
     * Sets the s5
     * @var string
     */
    function setS5($arg0) {
    	$this->s5 = $arg0;
    	$this->addModifiedColumn("s5");
    	return $this;
    }    
    
    /**
     * Returns the uid
     * @return string
     */
    function getUid() {
    	if (is_null($this->uid)) {
    		$this->uid = "";
    	}
    	return $this->uid;
    }
    
    /**
     * Sets the uid
     * @var string
     */
    function setUid($arg0) {
    	$this->uid = $arg0;
    	$this->addModifiedColumn("uid");
    	return $this;
    }
    
    /**
     * Returns the _uab
     * @return string
     */
    function getUserAgentBrowser() {
        return $this->getUab();
    }
    
    /**
     * Returns the _uav
     * @return string
     */
    function getUserAgentVersion() {
    	return $this->getUav();
    }
    
    /**
     * Returns the _uap
     * @return string
     */
    function getUserAgentPlatform() {
    	return $this->getUap();
    }
    
    /**
     * Returns the User Agent of the user
     * @return string
     */
    public static function getUserAgent() {
    	if (isset($_SERVER['HTTP_USER_AGENT'])) {
    		return $_SERVER['HTTP_USER_AGENT'];
    	}
    	return '';
    }
    
    /**
     * Returns the user agent information
     * @param string $user_agent
     * @return \Crossjoin\Browscap\Browscap
     */
    public static function getUserAgentInfo() {
    	$bc = new \Crossjoin\Browscap\Browscap();
    	// Get information about the current browser's user agent
    	$current_browser = $bc->getBrowser(self::getUserAgent());
    	return $current_browser;
    }
    
    /**
     * Sets the _o
     * $param array
     */
    function setO($arg0) {
        if (is_array($arg0)) {
            if (isset($arg0['_id'])) { $this->setOfferId($arg0['_id']); }
            if (isset($arg0['name'])) { $this->setOfferName($arg0['name']); }
        } else if (is_int($arg0)) {
            $this->setOfferId($arg0);
        }
        return $this;
    }
    
    /**
     * Returns the _o
     * @return array
     */
    function getO() {
    	$ret_val = array('_id' => $this->getOfferId(), 'name' => $this->getOfferName()); 
    	return (Object)$ret_val;
    }
    
    /**
     * Sets the _c
     * $param array
     */
    function setC($arg0) {
    	if (is_array($arg0)) {
    		if (isset($arg0['_id'])) { $this->setClientId($arg0['_id']); }
    		if (isset($arg0['name'])) { $this->setClientName($arg0['name']); }
    	} else if (is_int($arg0)) {
    		$this->setClientId($arg0);
    	}
    	return $this;
    }
    
    /**
     * Returns the _c
     * @return array
     */
    function getC() {
    	$ret_val = array('_id' => $this->getClientId(), 'name' => $this->getClientName());
    	return (Object)$ret_val;
    }
    
    /**
     * Returns the _ck
     * @return array
     */
    function getCk() {
    	$ret_val = array('_id' => $this->getCampaignId());
    	return (Object)$ret_val;
    }
    
    /**
     * Returns the _offer_id
     * @return integer
     */
    function getOfferId() {
    	if (is_null($this->_offer_id)) {
    		$this->_offer_id = 0;
    	}
    	return $this->_offer_id;
    }
    
    /**
     * Sets the _offer_id
     * @var integer
     */
    function setOfferId($arg0) {
    	$this->_offer_id = (int)$arg0;
    	$this->_offer = null;
    	$this->_offer_name = null;
    	$this->addModifiedColumn("_offer_id");
    	return $this;
    }
    
    /**
     * Returns the _client_id
     * @return integer
     */
    function getClientId() {
    	if (is_null($this->_client_id)) {
    		$this->_client_id = 0;
    	}
    	return $this->_client_id;
    }
    
    /**
     * Sets the client_id
     * @var integer
     */
    function setClientId($arg0) {
    	$this->_client_id = (int)$arg0;
    	$this->addModifiedColumn("_client_id");
    	return $this;
    }
    
    /**
     * Returns the _campaign_id
     * @return string
     */
    function getCampaignId() {
    	if (is_null($this->_campaign_id)) {
    		$this->_campaign_id = "";
    	}
    	return $this->_campaign_id;
    }
    
    /**
     * Sets the _campaign_id
     * @var string
     */
    function setCampaignId($arg0) {
    	$this->_campaign_id = $arg0;
    	$this->_campaign = null;
    	$this->addModifiedColumn("_campaign_id");
    	// Fetch the offer and client from the campaign
    	$this->setOfferId($this->getCampaign()->getOfferId());
    	$this->setClientId($this->getCampaign()->getClientId());
    	return $this;
    }
    
    /**
     * Returns the _offer_name
     * @return string
     */
    function getOfferName() {
    	if (is_null($this->_offer_name)) {
    	    if ($this->getOfferId() > 0) {
    		    $this->_offer_name = $this->getOffer()->getName();
    	    }
    	}
    	return $this->_offer_name;
    }
    
    /**
     * Sets the offer_name
     * @var string
     */
    function setOfferName($arg0) {
    	$this->_offer_name = $arg0;
    	$this->addModifiedColumn("_offer_name");
    	return $this;
    }
    
    /**
     * Returns the _client_name
     * @return string
     */
    function getClientName() {
    	if (is_null($this->_client_name)) {
    	    if ($this->getClientId() > 0) {
    		    $this->_client_name = $this->getClient()->getName();
    	    }
    	}
    	return $this->_client_name;
    }
    
    /**
     * Sets the _client_name
     * @var string
     */
    function setClientName($arg0) {
    	$this->_client_name = $arg0;
    	$this->addModifiedColumn("_client_name");
    	return $this;
    }
    
    /**
     * Returns the offer details
     * @return \Gun\Offer
     */
    function getOffer() {
        if (is_null($this->_offer)) {
            $this->_offer = new \Gun\Offer();
            $this->_offer->setId($this->getOfferId());
            $this->_offer->query();
        }
        return $this->_offer;
    }
    
    /**
     * Returns the client details
     * @return \Gun\Client
     */
    function getClient() {
    	if (is_null($this->_client)) {
    		$this->_client = new \Gun\Client();
    		$this->_client->setId($this->getClientId());
    		$this->_client->query();
    	}
    	return $this->_client;
    }   
    
    /**
     * Returns the campaign details
     * @return \Gun\Campaign
     */
    function getCampaign() {
    	if (is_null($this->_campaign)) {
    		$this->_campaign = new \Gun\Campaign();
    		$this->_campaign->setId($this->getCampaignId());
    		$this->_campaign->query();
    	}
    	return $this->_campaign;
    }
}