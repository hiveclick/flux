<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Server extends MongoForm {

	const SERVER_STATUS_ACTIVE = 1;
	const SERVER_STATUS_INACTIVE = 2;
	const SERVER_STATUS_DELETED = 3;
	
	const DEFAULT_DOCROOT_DIR = '/var/www/sites/';

	protected $status;
	protected $hostname;
	protected $alternate_hostname;

	protected $ip_address;
	protected $root_username;
	protected $root_password;
	
	protected $ftp_username;
	protected $ftp_password;
	protected $use_passive_mode;

	protected $fluxfe_lib_dir;
	protected $docroot_dir;
	protected $web_user;
	protected $web_group;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('server');
		$this->setDbName('admin');
	}

	/**
	 * Sets the id
	 * @var integer
	 */
	function setServerId($arg0) {
		return parent::setId($arg0);
	}

	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::SERVER_STATUS_ACTIVE;
		}
		return $this->status;
	}

	/**
	 * Sets the status
	 * @var integer
	 */
	function setStatus($arg0) {
		$this->status = (int)$arg0;
		$this->addModifiedColumn('status');
		return $this;
	}

	/**
	 * Returns the hostname
	 * @return string
	 */
	function getHostname() {
		if (is_null($this->hostname)) {
			$this->hostname = "";
		}
		return $this->hostname;
	}

	/**
	 * Sets the hostname
	 * @var string
	 */
	function setHostname($arg0) {
		$this->hostname = $arg0;
		$this->addModifiedColumn('hostname');
		return $this;
	}

	/**
	 * Returns the alternate_hostname
	 * @return string
	 */
	function getAlternateHostname() {
		if (is_null($this->alternate_hostname)) {
			$this->alternate_hostname = "";
		}
		return $this->alternate_hostname;
	}

	/**
	 * Sets the alternate_hostname
	 * @var string
	 */
	function setAlternateHostname($arg0) {
		$this->alternate_hostname = $arg0;
		$this->addModifiedColumn('alternate_hostname');
		return $this;
	}

	/**
	 * Returns the ip_address
	 * @return string
	 */
	function getIpAddress() {
		if (is_null($this->ip_address)) {
			$this->ip_address = "";
		}
		return $this->ip_address;
	}

	/**
	 * Sets the ip_address
	 * @var string
	 */
	function setIpAddress($arg0) {
		$this->ip_address = $arg0;
		$this->addModifiedColumn('ip_address');
		return $this;
	}

	/**
	 * Returns the root_username
	 * @return string
	 */
	function getRootUsername() {
		if (is_null($this->root_username)) {
			$this->root_username = "root";
		}
		return $this->root_username;
	}

	/**
	 * Sets the root_username
	 * @var string
	 */
	function setRootUsername($arg0) {
		$this->root_username = $arg0;
		$this->addModifiedColumn('root_username');
		return $this;
	}

	/**
	 * Returns the root_password
	 * @return string
	 */
	function getRootPassword() {
		if (is_null($this->root_password)) {
			$this->root_password = "";
		}
		return $this->root_password;
	}

	/**
	 * Sets the root_password
	 * @var string
	 */
	function setRootPassword($arg0) {
		$this->root_password = $arg0;
		$this->addModifiedColumn('root_password');
		return $this;
	}
	
	/**
	 * Returns the ftp_username
	 * @return string
	 */
	function getFtpUsername() {
	    if (is_null($this->ftp_username)) {
	        $this->ftp_username = "";
	    }
	    return $this->ftp_username;
	}
	
	/**
	 * Sets the ftp_username
	 * @var string
	 */
	function setFtpUsername($arg0) {
	    $this->ftp_username = $arg0;
	    $this->addModifiedColumn("ftp_username");
	    return $this;
	}
	
	/**
	 * Returns the ftp_password
	 * @return string
	 */
	function getFtpPassword() {
	    if (is_null($this->ftp_password)) {
	        $this->ftp_password = "";
	    }
	    return $this->ftp_password;
	}
	
	/**
	 * Sets the ftp_password
	 * @var string
	 */
	function setFtpPassword($arg0) {
	    $this->ftp_password = $arg0;
	    $this->addModifiedColumn("ftp_password");
	    return $this;
	}
	
	/**
	 * Returns the use_passive_mode
	 * @return boolean
	 */
	function getUsePassiveMode() {
	    if (is_null($this->use_passive_mode)) {
	        $this->use_passive_mode = false;
	    }
	    return $this->use_passive_mode;
	}
	
	/**
	 * Sets the use_passive_mode
	 * @var boolean
	 */
	function setUsePassiveMode($arg0) {
	    $this->use_passive_mode = $arg0;
	    $this->addModifiedColumn('use_passive_mode');
	    return $this;
	}

	/**
	 * Returns the fluxfe_lib_dir
	 * @return string
	 */
	function getFluxfeLibDir() {
		if (is_null($this->fluxfe_lib_dir)) {
			$this->fluxfe_lib_dir = "/home/flux/frontend/webapp/lib";
		}
		return $this->fluxfe_lib_dir;
	}

	/**
	 * Sets the Fluxfe_lib_dir
	 * @var string
	 */
	function setFluxfeLibDir($arg0) {
		$this->fluxfe_lib_dir = $arg0;
		$this->addModifiedColumn('fluxfe_lib_dir');
		return $this;
	}
	
	/**
	 * Returns the getRootDir()
	 * @return string
	 */
	function getRootDir() {
		$root_folder = $this->getDocrootDir();
		if (strpos($root_folder, 'docroot') !== false) {
			$root_folder = substr($root_folder, 0, strpos($root_folder, 'docroot'));
		}
		return $root_folder;
	}

	/**
	 * Returns the docroot_dir
	 * @return string
	 */
	function getDocrootDir() {
		if (is_null($this->docroot_dir)) {
			$this->docroot_dir = self::DEFAULT_DOCROOT_DIR;
		}
		return $this->docroot_dir;
	}

	/**
	 * Sets the docroot_dir
	 * @var string
	 */
	function setDocrootDir($arg0) {
		$this->docroot_dir = $arg0;
		$this->addModifiedColumn('docroot_dir');
		return $this;
	}

	/**
	 * Returns the web_user
	 * @return string
	 */
	function getWebUser() {
		if (is_null($this->web_user)) {
			$this->web_user = "apache";
		}
		return $this->web_user;
	}

	/**
	 * Sets the web_user
	 * @var string
	 */
	function setWebUser($arg0) {
		$this->web_user = $arg0;
		$this->addModifiedColumn('web_user');
		return $this;
	}

	/**
	 * Returns the web_group
	 * @return string
	 */
	function getWebGroup() {
		if (is_null($this->web_group)) {
			$this->web_group = "apache";
		}
		return $this->web_group;
	}

	/**
	 * Sets the web_group
	 * @var string
	 */
	function setWebGroup($arg0) {
		$this->web_group = $arg0;
		$this->addModifiedColumn('web_group');
		return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$offer_page = new self();
		$offer_page->getCollection()->ensureIndex(array('hostname' => 1), array('background' => true));
		return true;
	}
}