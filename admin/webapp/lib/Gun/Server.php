<?php
namespace Gun;

use Mojavi\Form\MongoForm;
use Mojavi\Logging\LoggerManager;

class Server extends MongoForm {

	const SERVER_STATUS_ACTIVE = 1;
	const SERVER_STATUS_INACTIVE = 2;
	const SERVER_STATUS_DELETED = 3;

	protected $status;
	protected $hostname;
	protected $alternate_hostname;

	protected $ip_address;
	protected $root_username;
	protected $root_password;

	protected $gunfe_lib_dir;
	protected $docroot_dir;
	protected $web_user;
	protected $web_group;

	protected $_status_name;

	private $ssh_session;
	private $force_ip_connection;
	
	private $offer_id;
	private $folder_name;
	private $domain;
	private $recreate_lib_folder;
	private $create_skeleton_folder;
	private $generate_virtualhost;

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
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if (is_null($this->_status_name)) {
			$this->_status_name = self::retrieveStatuses()[$this->getStatus()];
		}
		return $this->_status_name;
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
	 * Returns the gunfe_lib_dir
	 * @return string
	 */
	function getGunfeLibDir() {
		if (is_null($this->gunfe_lib_dir)) {
			$this->gunfe_lib_dir = "/home/gun/frontend/webapp/lib";
		}
		return $this->gunfe_lib_dir;
	}

	/**
	 * Sets the gunfe_lib_dir
	 * @var string
	 */
	function setGunfeLibDir($arg0) {
		$this->gunfe_lib_dir = $arg0;
		$this->addModifiedColumn('gunfe_lib_dir');
		return $this;
	}

	/**
	 * Returns the docroot_dir
	 * @return string
	 */
	function getDocrootDir() {
		if (is_null($this->docroot_dir)) {
			$this->docroot_dir = "/var/www/sites/";
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
	 * Returns the offer_id
	 * @return integer
	 */
	function getOfferId() {
		if (is_null($this->offer_id)) {
			$this->offer_id = 0;
		}
		return $this->offer_id;
	}
	
	/**
	 * Sets the offer_id
	 * @var integer
	 */
	function setOfferId($arg0) {
		$this->offer_id = (int)$arg0;
		$this->addModifiedColumn("offer_id");
		return $this;
	}
	
	/**
	 * Returns the folder_name
	 * @return string
	 */
	function getFolderName() {
		if (is_null($this->folder_name)) {
			$this->folder_name = "";
		}
		return $this->folder_name;
	}
	
	/**
	 * Sets the folder_name
	 * @var string
	 */
	function setFolderName($arg0) {
		$this->folder_name = $arg0;
		$this->addModifiedColumn("folder_name");
		return $this;
	}
	
	/**
	 * Returns the domain
	 * @return string
	 */
	function getDomain() {
		if (is_null($this->domain)) {
			$this->domain = "";
		}
		return $this->domain;
	}
	
	/**
	 * Sets the domain
	 * @var string
	 */
	function setDomain($arg0) {
		$this->domain = $arg0;
		$this->addModifiedColumn("domain");
		return $this;
	}
	
	/**
	 * Returns the recreate_lib_folder
	 * @return boolean
	 */
	function getRecreateLibFolder() {
		if (is_null($this->recreate_lib_folder)) {
			$this->recreate_lib_folder = false;
		}
		return $this->recreate_lib_folder;
	}
	
	/**
	 * Sets the recreate_lib_folder
	 * @var boolean
	 */
	function setRecreateLibFolder($arg0) {
		$this->recreate_lib_folder = $arg0;
		$this->addModifiedColumn("recreate_lib_folder");
		return $this;
	}
	
	/**
	 * Returns the create_skeleton_folder
	 * @return boolean
	 */
	function getCreateSkeletonFolder() {
		if (is_null($this->create_skeleton_folder)) {
			$this->create_skeleton_folder = false;
		}
		return $this->create_skeleton_folder;
	}
	
	/**
	 * Sets the create_skeleton_folder
	 * @var boolean
	 */
	function setCreateSkeletonFolder($arg0) {
		$this->create_skeleton_folder = $arg0;
		$this->addModifiedColumn("create_skeleton_folder");
		return $this;
	}
	
	/**
	 * Returns the generate_virtualhost
	 * @return boolean
	 */
	function getGenerateVirtualHost() {
		if (is_null($this->generate_virtualhost)) {
			$this->generate_virtualhost = false;
		}
		return $this->generate_virtualhost;
	}
	
	/**
	 * Sets the generate_virtualhost
	 * @var boolean
	 */
	function setGenerateVirtualHost($arg0) {
		$this->generate_virtualhost = $arg0;
		$this->addModifiedColumn("generate_virtualhost");
		return $this;
	}

	/**
	 * Returns the array of campaign statuses
	 * @return multitype:string
	 */
	public static function retrieveStatuses() {
		return array(
				self::SERVER_STATUS_ACTIVE => 'Active',
				self::SERVER_STATUS_INACTIVE => 'Inactive',
				self::SERVER_STATUS_DELETED => 'Deleted'
		);
	}

	/**
	 * Returns the force_ip_connection
	 * @return boolean
	 */
	function getForceIpConnection() {
		if (is_null($this->force_ip_connection)) {
			$this->force_ip_connection = false;
		}
		return $this->force_ip_connection;
	}

	/**
	 * Sets the force_ip_connection
	 * @var boolean
	 */
	function setForceIpConnection($arg0) {
		$this->force_ip_connection = $arg0;
		return $this;
	}

	/**
	 * Returns the ssh_session
	 * @return resource
	 */
	function getSshSession() {
		if (is_null($this->ssh_session)) {
			$this->ssh_session = $this->connect();
		}
		return $this->ssh_session;
	}
	/**
	 * Sets the ssh_session
	 * @param resource
	 */
	function setSshSession($arg0) {
		$this->ssh_session = $arg0;
		return $this;
	}

	/**
	 * Connects to the server and creates a session
	 * @return resource
	 */
	function connect() {
	    $con = null;
		if (!$this->getForceIpConnection()) {
			if (trim($this->getHostname()) != '') {
				if (($con = ssh2_connect($this->getHostname())) === false) {
					if (($con = @ssh2_connect($this->getIpAddress())) === false) {
						throw new \Exception('Cannot connect to remote host ' . $this->getHostname() . ' or ' . $this->getIpAddress());
					}
				}
			} else if (trim($this->getIpAddress()) != '') {
				if (($con = ssh2_connect($this->getIpAddress())) === false) {
					throw new \Exception('Cannot connect to remote host ' . $this->getIpAddress());
				}
			}
		} else {
			if (trim($this->getIpAddress()) != '') {
				if (($con = ssh2_connect($this->getIpAddress())) === false) {
					throw new \Exception('Cannot connect to remote host ' . $this->getIpAddress());
				}
			}
		}
		if (is_null($con)) {
		    throw new \Exception('Cannot connect to remote host ' . $this->getIpAddress());
		}
		
		if (ssh2_auth_password($con, $this->getRootUsername(), $this->getRootPassword()) !== false) {
			return $con;
		} else {
			throw new \Exception('Cannot login to ' . $this->getAdminIpAddress() . ' using password');
		}
		
		return null;
	}

	/**
	 * Disconnects to the server and creates a session
	 * @return resource
	 */
	function disconnect() {
		$this->setSshSession(null);
		return true;
	}

	/**
	 * Looks up the hostname on the remote server
	 * @return string
	 */
	function lookupHostname() {
		$hostname = $this->runRemoteCommand('hostname');
		$this->setHostname($hostname);
		return $hostname;
	}

	/**
	 * Runs a command on the remote server
	 * @return string
	 */
	function runRemoteCommand($cmd) {
		if (($stream = @ssh2_exec($this->getSshSession(), $cmd, 'xterm')) !== false) {
			stream_set_blocking($stream, true);
			$cmd_response = stream_get_contents($stream);
			@fclose($stream);
			return $cmd_response;
		} else {
			throw new \Exception('Cannot execute commands on remote server ' . $this->getHostname());
		}
	}
	
	/**
	 * Read a file from a remote location
	 * @return string
	 */
	function readRemoteFile($dest) {
	    $temporary_name = tempnam("/tmp/", "remote");
	    if (!@ssh2_scp_recv($this->getSshSession(), $dest, $temporary_name)) {
			throw new \Exception("Could not find " . $dest . " on remote server.");
		}
		if (file_exists($temporary_name)) {
		    return file_get_contents($temporary_name);
		} else {
		    return false;
		}
	}

	/**
	 * Copies a source file to the destination location
	 * @return string
	 */
	function writeRemoteFile($src_contents, $dest, $permissions = 0664) {
		$temporary_name = tempnam("/tmp/", "remote");
		file_put_contents($temporary_name, $src_contents);
		if (!ssh2_scp_send($this->getSshSession(), $temporary_name, $dest, $permissions)) {
			throw new \Exception("Could not copy to " . $dest . " on remote server.  Check that scp is installed with <code>yum install openssh-clients</code>");
		}
		return $dest;
	}

	/**
	 * Copies a source file to the destination location
	 * @return string
	 */
	function copyFile($src, $dest) {
		if (!@ssh2_scp_send($this->getSshSession(), $src, $dest)) {
			throw new \Exception("Could not copy script " . $src . " to remote server.  Check that scp is installed with <code>yum install openssh-clients</code>");
		}
		return $dest;
	}
	
	/**
	 * Creates the remote folder skeleton
	 * @return boolean
	 */
	function recreateLibFolder($offer) {
	    $init_php_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/init.php");
	    $config_ini_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/config.ini");
	    
	    $config_ini_contents = str_replace("[[FE_LIB]]", "/home/gunfe/frontend/webapp/lib", $config_ini_contents);
	    if (defined("MO_API_URL")) {
	    	$config_ini_contents = str_replace("[[API_URL]]", MO_API_URL, $config_ini_contents);
	    } else {
	    	/* @todo setup global api url somewhere and use that instead */
	    	$config_ini_contents = str_replace("[[API_URL]]", 'http://api.gun.local', $config_ini_contents);
	    }
	    $config_ini_contents = str_replace("[[OFFER_KEY]]", $offer->getFolderName(), $config_ini_contents);
	    $config_ini_contents = str_replace("[[COOKIE_NAME]]", $offer->getFolderName(), $config_ini_contents);
	    
	    $root_folder = "/var/www/sites/" . $offer->getFolderName();
	    
	    $install_php_contents = <<<EOL
<?php
	\$root_folder = "/var/www/sites/{$offer->getFolderName()}";
	if (!file_exists(\$root_folder)) {
		mkdir(\$root_folder);
	}
	if (!file_exists(\$root_folder . "/lib")) {
		mkdir(\$root_folder . "/lib");
	}
	if (!file_exists(\$root_folder . "/lib/cache")) {
		mkdir(\$root_folder . "/lib/cache");
	}
	chgrp(\$root_folder . "/lib/cache", 'apache');
	chmod(\$root_folder . "/lib/cache", 0775);
EOL;
	    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Saving install contents to /tmp/offer_" . $offer->getId() . "_install.php");
	    $this->writeRemoteFile($install_php_contents, "/tmp/offer_" . $offer->getId() . "_install.php", 0775);
	    $this->runRemoteCommand("php /tmp/offer_" . $offer->getId() . "_install.php");
	    
	    $this->writeRemoteFile($init_php_contents, $root_folder . "/lib/init.php");
	    $this->writeRemoteFile($config_ini_contents, $root_folder . "/lib/config.ini");
	}

	/**
	 * Creates the remote folder skeleton
	 * @return boolean
	 */
	function createRemoteFolderSkeleton($offer) {
		$index_php_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/index.php");
		$virtualhost_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/virtualhost");
		
		$root_folder = "/var/www/sites/" . $offer->getFolderName();

		$install_php_contents = <<<EOL
<?php
	\$root_folder = "/var/www/sites/{$offer->getFolderName()}";
	if (!file_exists(\$root_folder)) {
		mkdir(\$root_folder);
	}
	if (!file_exists(\$root_folder . "/docroot")) {
		mkdir(\$root_folder . "/docroot");
	}
	if (!file_exists(\$root_folder . "/docroot/pages")) {
		mkdir(\$root_folder . "/docroot/pages");
	}
	if (!file_exists(\$root_folder . "/docroot/v1")) {
		mkdir(\$root_folder . "/docroot/v1");
	}
	if (!file_exists(\$root_folder . "/docroot/js")) {
		mkdir(\$root_folder . "/docroot/js");
	}
	if (!file_exists(\$root_folder . "/docroot/css")) {
		mkdir(\$root_folder . "/docroot/css");
	}
	if (!file_exists(\$root_folder . "/docroot/images")) {
		mkdir(\$root_folder . "/docroot/images");
	}
EOL;
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Saving install contents to /tmp/offer_" . $offer->getId() . "_install.php");
		$this->writeRemoteFile($install_php_contents, "/tmp/offer_" . $offer->getId() . "_install.php", 0775);
		$this->runRemoteCommand("php /tmp/offer_" . $offer->getId() . "_install.php");
		
		if (trim($this->runRemoteCommand('if [ -f ' . $root_folder . '/docroot/index.php ];then echo "1";else echo "0";fi')) == '0') {
			$this->writeRemoteFile($index_php_contents, $root_folder . "/docroot/index.php");
		}
		
		if (trim($this->runRemoteCommand('if [ -f ' . $root_folder . '/docroot/pages/index.php ];then echo "1";else echo "0";fi')) == '0') {
			$this->writeRemoteFile(file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/first_page.php"), $root_folder . "/docroot/pages/index.php");
			$this->writeRemoteFile(file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/header.php"), $root_folder . "/docroot/pages/header.php");
			$this->writeRemoteFile(file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/footer.php"), $root_folder . "/docroot/pages/footer.php");
		}
		
		if (trim($this->runRemoteCommand('if [ -f ' . $root_folder . '/docroot/v1/index.php ];then echo "1";else echo "0";fi')) == '0') {
			$this->writeRemoteFile(file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/v1_first_page.php"), $root_folder . "/docroot/v1/index.php");
		}
		return true;
	}
	
	/**
	 * Generates the virtualhost file for apache
	 * @return boolean
	 */
	function generateVirtualHost($offer) {
		$virtualhost_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/virtualhost");

		$root_folder = "/var/www/sites/" . $offer->getFolderName() . '/docroot';
	
		$virtualhost_contents = str_replace("[DOCUMENTROOT]", $root_folder, $virtualhost_contents);
		$virtualhost_contents = str_replace("[DOMAIN]", $this->getDomain(), $virtualhost_contents);
		$virtualhost_contents = str_replace("[DOMAIN2]", str_replace('www.', '', $this->getDomain()), $virtualhost_contents);		

		if (trim($this->runRemoteCommand('if [ -f /etc/httpd/conf.d/' . $offer->getFolderName() . '.conf ];then echo "1";else echo "0";fi')) == '0') {
		    \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Writing virtualhost file: " . '/etc/httpd/conf.d/' . $offer->getFolderName() . '.conf');
			$this->writeRemoteFile($virtualhost_contents, '/etc/httpd/conf.d/' . $offer->getFolderName() . '.conf');
		}
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Restarting Apache");
		$apache_response = $this->runRemoteCommand("/sbin/service httpd graceful");
		// If apache fails to restart, then remove the virtualhost, try again, and throw an error
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($apache_response, true));
		return true;
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