<?php
namespace Flux;

class Server extends Base\Server {

	private $ssh_session;
	private $force_ip_connection;
	
	private $offer_id;
	private $folder_name;
	private $domain;
	private $flush_offer_cache;
	private $recreate_lib_folder;
	private $create_skeleton_folder;
	private $generate_virtualhost;
	private $force_overwrite;
	
	private $html_input_element_id;
	private $files;
	private $_connection_id;
	
	/**
	 * Returns the files
	 * @return string
	 */
	function getFiles() {
		if (is_null($this->files)) {
			$this->files = $this->downloadFiles();
		}
		return $this->files;
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
	 * Returns the folder name
	 * @return string
	 */
	function setFolderName($arg0) {
		if (strpos($arg0, '/') === 0) {
			$this->folder_name = substr($arg0, 1);
		} else if (strpos($arg0, './') === 0) {
			$this->folder_name = substr($arg0, 2);
		} else if (strpos($arg0, '../') === 0) {
			$this->folder_name = substr($arg0, 3);
		} else if (strpos($arg0, '.') === 0) {
			$this->folder_name = substr($arg0, 1);
		} else {
		   $this->folder_name = $arg0;
		}
		return $this;
	}
	
	/**
	 * Returns the html_input_element_id
	 * @return string
	 */
	function getHtmlInputElementId() {
		if (is_null($this->html_input_element_id)) {
			$this->html_input_element_id = "";
		}
		return $this->html_input_element_id;
	}
	
	/**
	 * Sets the html_input_element_id
	 * @var string
	 */
	function setHtmlInputElementId($arg0) {
		$this->html_input_element_id = $arg0;
		$this->addModifiedColumn('html_input_element_id');
		return $this;
	}

	/**
	 * Returns the _status_name
	 * @return string
	 */
	function getStatusName() {
		if ($this->getStatus() == self::SERVER_STATUS_ACTIVE) {
			return "Active";
		} else if ($this->getStatus() == self::SERVER_STATUS_INACTIVE) {
			return "Inactive";
		} else if ($this->getStatus() == self::SERVER_STATUS_DELETED) {
			return "Deleted";
		} else {
			return "Unknown Status";
		}
	}
	
	/**
	 * Returns the offer_id
	 * @return integer
	 */
	function getOfferId() {
		if (is_null($this->offer_id)) {
			$this->offer_id = null;
		}
		return $this->offer_id;
	}
	
	/**
	 * Sets the offer_id
	 * @var integer
	 */
	function setOfferId($arg0) {
		$this->offer_id = $arg0;
		$this->addModifiedColumn("offer_id");
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
	 * Returns the flush_offer_cache
	 * @return boolean
	 */
	function getFlushOfferCache() {
		if (is_null($this->flush_offer_cache)) {
			$this->flush_offer_cache = false;
		}
		return $this->flush_offer_cache;
	}
	
	/**
	 * Sets the flush_offer_cache
	 * @var boolean
	 */
	function setFlushOfferCache($arg0) {
		$this->flush_offer_cache = $arg0;
		$this->addModifiedColumn("flush_offer_cache");
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
	 * Returns the force_overwrite
	 * @return boolean
	 */
	function getForceOverwrite() {
		if (is_null($this->force_overwrite)) {
			$this->force_overwrite = 0;
		}
		return $this->force_overwrite;
	}
	
	/**
	 * Sets the force_overwrite
	 * @var boolean
	 */
	function setForceOverwrite($arg0) {
		$this->force_overwrite = $arg0;
		$this->addModifiedColumn("force_overwrite");
		return $this;
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
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+

	/**
	 * Returns the user based on the criteria
	 * @return Flux\User
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
		if (trim($this->getHostname()) != '') {
			$criteria['hostname'] = new \MongoRegex("/" . $this->getHostname() . "/i");
		}
		return parent::queryAll($criteria, $hydrate, $fields);
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
			throw new \Exception('Cannot login to ' . $this->getIpAddress() . ' using password');
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
		if (($stream = ssh2_exec($this->getSshSession(), $cmd, 'xterm')) !== false) {
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
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Sending local file " . $temporary_name . " to " . $dest);
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
	 * @param $offer \Flux\Offer
	 * @return boolean
	 */
	function recreateLibFolder($offer) {
		if ($this->getRootDir() == self::DEFAULT_DOCROOT_DIR ||
			(dirname($this->getRootDir()) == dirname(self::DEFAULT_DOCROOT_DIR) && basename($this->getRootDir()) == basename(self::DEFAULT_DOCROOT_DIR))
		) {
			throw new \Exception('Cannot save to the default docroot directory, please set an appropriate subdirectory');
		}
		
		$init_php_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/init.php");
		$config_ini_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/config.ini");
		$offer_key = $offer->getId();
		
		$config_ini_contents = str_replace("[[FE_LIB]]", "/home/fluxfe/frontend/webapp/lib", $config_ini_contents);
		if (defined("MO_API_URL")) {
			$config_ini_contents = str_replace("[[API_URL]]", MO_API_URL, $config_ini_contents);
		} else {
			/* @todo setup global api url somewhere and use that instead */
			$config_ini_contents = str_replace("[[API_URL]]", 'http://api.flux.local', $config_ini_contents);
		}
		$config_ini_contents = str_replace("[[OFFER_KEY]]", $offer_key, $config_ini_contents);
		$config_ini_contents = str_replace("[[COOKIE_NAME]]", 'flux_' . $offer->getId(), $config_ini_contents);
		$config_ini_contents = str_replace("[[DEFAULT_CAMPAIGN]]", $offer->getDefaultCampaignId(), $config_ini_contents);
		
		$install_php_contents = <<<EOL
<?php
	\$root_folder = "{$this->getRootDir()}";
	\$docroot_folder = "{$offer->getDocrootDir()}";
	\$cache_user = "{$this->getWebUser()}";
	\$cache_group = "{$this->getWebGroup()}";
	if (!file_exists(\$root_folder)) {
		mkdir(\$root_folder, 0775, true);
		chown(\$root_folder, \$cache_user);
		chgrp(\$root_folder, \$cache_group);
	}
	if (!file_exists(\$root_folder . "/lib")) {
		mkdir(\$root_folder . "/lib", 0775, true);
		chown(\$root_folder . "/lib", \$cache_user);
		chgrp(\$root_folder . "/lib", \$cache_group);
	}
	if (!file_exists(\$docroot_folder)) {
		mkdir(\$docroot_folder, 0775, true);
		chown(\$root_folder . "/lib", \$cache_user);
		chgrp(\$root_folder . "/lib", \$cache_group);
	}
	if (!file_exists(\$root_folder . "/docroot")) {
		mkdir(\$root_folder . "/docroot", 0775, true);
		chown(\$root_folder . "/docroot", \$cache_user);
		chgrp(\$root_folder . "/docroot", \$cache_group);
	}
	if (!file_exists(\$docroot_folder . "/.cache")) {
		mkdir(\$docroot_folder . '/.cache/', 0775, true);
		chown(\$docroot_folder . '/.cache/', \$cache_user);
		chgrp(\$docroot_folder . '/.cache/', \$cache_group);
	}
	if (file_exists(\$docroot_folder . "/.cache/config.php")) {
		@unlink(\$docroot_folder . "/.cache/config.php");
	}
	
	\$cmd = "chown \$cache_user:\$cache_group \$root_folder -Rf";
	shell_exec(\$cmd);
	\$cmd = "chmod 0775 \$root_folder -Rf";
	shell_exec(\$cmd);
EOL;
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Saving install contents to /tmp/offer_" . $offer->getId() . "_install.php");
		$this->writeRemoteFile($install_php_contents, "/tmp/offer_" . $offer->getId() . "_install.php", 0775);
		$this->runRemoteCommand("php /tmp/offer_" . $offer->getId() . "_install.php");
		
		$this->writeRemoteFile($init_php_contents, $this->getRootDir() . "/lib/init.php");
		$this->writeRemoteFile($config_ini_contents, $this->getDocrootDir() . "/config.ini");
		
		$this->runRemoteCommand("chown " . $this->getWebUser() . ":" . $this->getWebGroup() . " " . $this->getRootDir() . " -Rf");
		$this->runRemoteCommand("chmod 0777 " . $this->getRootDir() . " -Rf");
	}

	/**
	 * Creates the remote folder skeleton
	 * @return boolean
	 */
	function createRemoteFolderSkeleton($offer) {
		$this->recreateLibFolder($offer);
		if ($this->getRootDir() == self::DEFAULT_DOCROOT_DIR ||
			(dirname($this->getRootDir()) == dirname(self::DEFAULT_DOCROOT_DIR) && basename($this->getRootDir()) == basename(self::DEFAULT_DOCROOT_DIR))
		) {
			throw new \Exception('Cannot save to the default docroot directory, please set an appropriate subdirectory');
		}
		
		$index_php_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/index.php");
		$virtualhost_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/virtualhost");
		$wp_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/wp-config.php");
		$offer_key = (basename($this->getRootDir()) . (trim($offer->getFolderName()) != '' ? '.' : '') . trim($offer->getFolderName()));
		
		// Now upload the wordpress tar and extract it only if wp-config.php doesn't exist
		if (trim($this->runRemoteCommand('if [ -f ' . $this->getRootDir() . '/docroot/wp-config.php ];then echo "1";else echo "0";fi')) == '0') {
			$this->runRemoteCommand('wget -O ' . $this->getDocrootDir() . '/latest.tar.gz https://wordpress.org/latest.tar.gz');
			$this->runRemoteCommand('/bin/tar xzvf ' . $this->getDocrootDir() . '/latest.tar.gz --skip-old-files --strip-components=1 -C ' . $this->getDocrootDir());
			$db_prefix = substr($this->getDomain(), 0, strrpos($this->getDomain(), '.'));
			$db_prefix = preg_replace("/[^a-zA-Z0-9]/", "", $db_prefix);
			$db_prefix = ('wp_' . preg_replace("/^www/", "", $db_prefix));
			if ($db_prefix == 'wp_') { throw new \Exception('We cannot set the mysql database from the domain name, please check that the domain name is properly formatted.'); }
			$wp_contents = str_replace('[MYSQLUSERNAME]', $this->getMysqlUsername(), $wp_contents);
			$wp_contents = str_replace('[MYSQLPASSWORD]', $this->getMysqlPassword(), $wp_contents);
			$wp_contents = str_replace('[MYSQLDB]', $db_prefix, $wp_contents);
			$wp_contents = str_replace('[TABLEPREFIX]', 'wp_', $wp_contents);
			$this->writeRemoteFile($wp_contents, $this->getRootDir() . '/docroot/wp-config.php');
			
			$this->runRemoteCommand('/usr/bin/mysqladmin create ' . $db_prefix);			
		}
		
		if (trim($this->runRemoteCommand('if [ -f ' . $this->getRootDir() . '/docroot/wp-content/flux.php ];then echo "1";else echo "0";fi')) == '0') {
			$this->copyFile(MO_WEBAPP_DIR . '/meta/frontend/flux.php', $this->getDocrootDir() . '/wp-content/flux.php');
		}
		
		if (trim($this->runRemoteCommand('if [ -f ' . $this->getRootDir() . '/docroot/shortcodes-ultimate-maker.zip ];then echo "1";else echo "0";fi')) == '0') {
			$this->copyFile(MO_WEBAPP_DIR . '/meta/frontend/shortcodes-ultimate-maker.zip', $this->getDocrootDir() . '/shortcodes-ultimate-maker.zip');
		}
		
		if (trim($this->runRemoteCommand('if [ -f ' . $this->getRootDir() . '/docroot/wp-cli.phar ];then echo "1";else echo "0";fi')) == '0') {
			$this->runRemoteCommand('wget -O ' . $this->getDocrootDir() . '/wp-cli.phar https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar');
			$this->runRemoteCommand('chmod 0775 ' . $this->getDocrootDir() . '/wp-cli.phar');
		}
		
		$this->runRemoteCommand("chown " . $this->getWebUser() . ":" . $this->getWebGroup() . " " . $this->getRootDir() . " -Rf");
		$this->runRemoteCommand("chmod 0777 " . $this->getRootDir() . " -Rf");
		
		$install_php_contents = <<<EOL
<?php
	\$root_folder = "{$this->getRootDir()}";
	\$docroot_folder = "{$offer->getDocrootDir()}";
	\$domain = "{$this->getDomain()}";
	\$cache_user = "{$this->getWebUser()}";
	\$cache_group = "{$this->getWebGroup()}";
	if (!file_exists(\$docroot_folder . "/wp-cli.phar")) {
		die("Missing wp-cli.phar, please install wordpress manually.");
	}
	\$cmd = "chown {$this->getWebGroup()}:{$this->getWebUser()} {$this->getRootDir()} -Rf";
	\$result = shell_exec(\$cmd);
	echo \$result . "\n";
	
	\$cmd = "chmod 0775 {$this->getRootDir()} -Rf";
	\$result = shell_exec(\$cmd);
	echo \$result . "\n";	
	
	echo "Installing wordpress...\n";	
	\$cmd = "ls -l {$offer->getDocrootDir()};";
	echo \$cmd . "\n";
	\$result = shell_exec(\$cmd);
	echo \$result . "\n";
	
	\$cmd = "cd {$offer->getDocrootDir()};sudo -u {$this->getWebUser()} ./wp-cli.phar core install --url=\"{$this->getDomain()}\" --title=\"{$this->getDomain()}\" --admin_user=admin --admin_password=ncc1701b --admin_email=mark@hiveclick.com --skip-email";
	echo \$cmd . "\n";
	\$result = shell_exec(\$cmd);
	echo \$result . "\n";
EOL;
		
		$plugins = array(
				'wordpress-seo' => 'wordpress-seo',
				'shortcodes-ultimate' => 'shortcodes-ultimate',
				'shortcodes-ultimate-maker' => $this->getDocrootDir() . '/shortcodes-ultimate-maker.zip',
				'wptouch' => 'wptouch'
		);
		foreach ($plugins as $key => $plugin) {
			$install_php_contents .= <<<EOL
				// Install script for plugin {$key}
				\$cmd = "cd {$offer->getDocrootDir()};sudo -u {$this->getWebUser()} ./wp-cli.phar plugin list --name=\"{$key}\" | grep \"{$key}\"";
				\$plugin_installed = shell_exec(\$cmd);
				if (trim(\$plugin_installed) == '') {
					echo "Installing plugin {$key}...\n";
					\$cmd = "cd {$offer->getDocrootDir()};sudo -u {$this->getWebUser()} ./wp-cli.phar plugin install \"{$plugin}\" --activate";
					\$result = shell_exec(\$cmd);
					echo \$result . "\n";				
				}
				\$cmd = "cd {$offer->getDocrootDir()};sudo -u {$this->getWebUser()} ./wp-cli.phar plugin list --name=\"{$key}\" --status=inactive | grep \"{$key}\"";
				\$plugin_inactive = shell_exec(\$cmd);
				if (trim(\$plugin_inactive) != '') {
					echo "Activating plugin {$key}...\n";
					\$cmd = "cd {$offer->getDocrootDir()};sudo -u {$this->getWebUser()} ./wp-cli.phar plugin activate \"{$key}\"";
					\$result = shell_exec(\$cmd);
					echo \$result . "\n";				
				}
EOL;

		}
		
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Saving install contents to /tmp/offer_" . $offer->getId() . "_install_wp.php");
		$this->writeRemoteFile($install_php_contents, "/tmp/offer_" . $offer->getId() . "_install_wp.php", 0775);
		$result = $this->runRemoteCommand("php /tmp/offer_" . $offer->getId() . "_install_wp.php");
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Running /tmp/offer_" . $offer->getId() . "_install_wp.php: " . $result);
		return true;
	}
	
	/**
	 * Clears cache entries for this offer
	 * @param $offer \Flux\Offer
	 * @return boolean
	 */
	function clearConfigCache($offer) {
		$cmd = 'if [ -f ' . $this->getDocrootDir() . '/.cache/config.php ];then rm ' . $this->getDocrootDir() . '/.cache/config.php;fi';
		
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Sending request to " . $this->getHostname() . ': ' . $cmd);
		$this->runRemoteCommand($cmd);	
		
		return true;
	}
	
	/**
	 * Clears cache entries for this offer
	 * @return boolean
	 */
	function clearOfferCache($offer) {
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Sending request to " . 'http://' . $this->getHostname() . '/admin/flush-offer-cache.json?' . http_build_query(array('_id' => $offer->getId()), '&'));
		
		$response = \Mojavi\Util\Ajax::sendAjax('/admin/flush-offer-cache.json', array('_id' => $offer->getId()), \Mojavi\Request\Request::POST, 'http://' . $this->getHostname());
		if (isset($response['RESULT']) && $response['RESULT'] == 'SUCCESS') {
			return true;
		}
		return false;
	}
	
	/**
	 * Clears cache entries for this offer
	 * @return boolean
	 */
	function clearCampaignCache($campaign) {
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Sending request to " . 'http://' . $this->getHostname() . '/admin/flush-campaign-cache.json?' . http_build_query(array('_id' => $campaign->getKey()), '&'));
		
		$response = \Mojavi\Util\Ajax::sendAjax('/admin/flush-campaign-cache.json', array('_id' => $campaign->getKey()), \Mojavi\Request\Request::POST, 'http://' . $this->getHostname());
		if (isset($response['RESULT']) && $response['RESULT'] == 'SUCCESS') {
			return true;
		}
		return false;
	}
	
	/**
	 * Generates the virtualhost file for apache
	 * @return boolean
	 */
	function generateVirtualHost($offer) {
		// Make sure we aren't pushing to the default folder
		if ($this->getRootDir() == self::DEFAULT_DOCROOT_DIR ||
			(dirname($this->getRootDir()) == dirname(self::DEFAULT_DOCROOT_DIR) && basename($this->getRootDir()) == basename(self::DEFAULT_DOCROOT_DIR))
		) {
			throw new \Exception('Cannot save to the default docroot directory, please set an appropriate subdirectory');
		}
		$offer_key = (basename($this->getRootDir()) . (trim($offer->getFolderName()) != '' ? '.' : '') . trim($offer->getFolderName()));
				
		$virtualhost_contents = file_get_contents(MO_WEBAPP_DIR . "/meta/frontend/virtualhost");		
		$virtualhost_contents = str_replace("[DOCUMENTROOT]", $this->getDocrootDir(), $virtualhost_contents);
		$virtualhost_contents = str_replace("[DOMAIN]", $this->getDomain(), $virtualhost_contents);
		$virtualhost_contents = str_replace("[DOMAIN2]", str_replace('www.', '', $this->getDomain()), $virtualhost_contents);		

		if (trim($this->runRemoteCommand('if [ -f /etc/httpd/conf.d/' . $offer_key . '.conf ];then echo "1";else echo "0";fi')) == '0') {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Writing virtualhost file: " . '/etc/httpd/conf.d/' . $offer_key . '.conf');
			$this->writeRemoteFile($virtualhost_contents, '/etc/httpd/conf.d/' . $offer_key . '.conf');
		} else if ($this->getForceOverwrite()) {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Writing virtualhost file: " . '/etc/httpd/conf.d/' . $offer_key . '.conf');
			$this->writeRemoteFile($virtualhost_contents, '/etc/httpd/conf.d/' . $offer_key . '.conf');
		} else {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Virtualhost file: " . '/etc/httpd/conf.d/' . $offer_key . '.conf already exists, skipping');
			throw new \Exception('Virtualhost file already exists and will not be overwritten (' . $offer_key . '.conf)');
		}
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Restarting Apache");
		$cmd = "nohup /home/fluxfe/frontend/webapp/meta/crons/reload_vhost.sh";
		$apache_response = $this->runRemoteCommand($cmd);
		// If apache fails to restart, then remove the virtualhost, try again, and throw an error
		return true;
	}
	
	/**
	 * Returns the FTP Connection id, or connects if not already connected
	 * @return resource|null
	 */
	function getFtpConnection() {
		if (is_null($this->_connection_id)) {
			$this->_connection_id = @ftp_connect($this->getHostname(), 21, 5);
			if ($this->_connection_id !== false) {
				if (is_null($this->_connection_id)) {
					throw new \Exception('Cannot connect to ftp "' . $this->getHostname() . '" because the _connection_id is null');
				}
				if (!@ftp_login($this->_connection_id, $this->getFtpUsername(), $this->getFtpPassword())) {
					throw new \Exception('We are able to connect to "' . $this->getHostname() . '", but cannot login using the username "' . $this->getFtpUsername() . '" and password');
				}
			} else {
				throw new \Exception('Cannot connect to "' . $this->getHostname() . '", check the hostname and that <code>vsftpd</code> is running');
			}
		}
		return $this->_connection_id;
	}
	
	/**
	 * Downloads a list of files from the ftp server
	 * Format of returned array is
	 *  - [type] = directory|file
	 *  - [rights] = drwxrwx---
	 *  - [number] = integer
	 *  - [user] = owner name
	 *  - [group] = group name
	 *  - [size] = size in bytes
	 *  - [month] = last modified month
	 *  - [day] = last modified day
	 *  - [time] = last modified time
	 * @return array
	 */
	private function downloadFiles() {
		try {
			$connection = $this->getFtpConnection();
				
			// Strip the first slash from folder names
			if (strpos($this->getFolderName(), '/') === 0) {
				$folder_name = substr($this->getFolderName(), 1);
			} else {
				$folder_name = $this->getFolderName();
			}
				
			// Enable passive mode
			if ($this->getUsePassiveMode()) {
				ftp_pasv($connection, true);
			}
				
			// Pull down the list of files
			$raw_list = ftp_rawlist($connection, $folder_name);
			if (is_array($raw_list)) {
				$file_list = array();
				foreach ($raw_list as $list_item) {
					$chunks = preg_split("/\s+/", $list_item);
					list($item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], $item['month'], $item['day'], $item['time']) = $chunks;
					$item['type'] = $chunks[0]{0} === 'd' ? 'directory' : 'file';
					array_splice($chunks, 0, 8);
					$filename = implode(" ", $chunks);
					// Apply a file filter if we have one
					$file_list[$filename] = $item;
				}
				return $file_list;
			}
		} catch (\Exception $e) {
			throw $e;
		}
		return array();
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