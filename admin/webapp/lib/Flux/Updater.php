<?php
namespace Flux;

use Mojavi\Form\CommonForm;

class Updater extends CommonForm {
    
    const PACKAGE_NAME = "flux";
    const UPDATE_FILE = "/tmp/update_status.json";
    
    protected $is_updating;
    protected $percent_complete;
    protected $update_message;
    
    protected $installed_version;
    protected $newest_version;
    protected $installed_package;
    protected $newest_package;
    protected $update_available;
    
    /**
     * Returns the is_updating
     * @return boolean
     */
    function getIsUpdating() {
        if (is_null($this->is_updating)) {
            if (file_exists(self::UPDATE_FILE)) {
                if (strtotime('now') - filemtime(self::UPDATE_FILE) < 300) {
                    // If the update file exists AND it has been updated in the past 5 minutes, then we are probably updating
                    /** @todo possibly check for the existence of a yum update process */
                    $this->is_updating = true;
                } else {
                    $this->is_updating = false;
                }
            } else {
                $this->is_updating = false;
            }
        }
        return $this->is_updating;
    }
    
    /**
     * Sets the is_updating
     * @var boolean
     */
    function setIsUpdating($arg0) {
        $this->is_updating = $arg0;
        $this->addModifiedColumn("is_updating");
        return $this;
    }
    
    /**
     * Returns the percent_complete
     * @return string
     */
    function getPercentComplete() {
        if (is_null($this->percent_complete)) {
            $this->percent_complete = 0;
        }
        return $this->percent_complete;
    }
    
    /**
     * Sets the percent_complete
     * @var string
     */
    function setPercentComplete($arg0) {
        $this->percent_complete = $arg0;
        $this->addModifiedColumn("percent_complete");
        return $this;
    }
    
    /**
     * Returns the update_message
     * @return string
     */
    function getUpdateMessage() {
        if (is_null($this->update_message)) {
            $this->update_message = "";
        }
        return $this->update_message;
    }
    
    /**
     * Sets the update_message
     * @var string
     */
    function setUpdateMessage($arg0) {
        $this->update_message = $arg0;
        $this->addModifiedColumn("update_message");
        return $this;
    }
    
    /**
     * Returns the installed_version
     * @return integer
     */
    function getInstalledVersion() {
        if (is_null($this->installed_version)) {
            $this->installed_version = 0;
        }
        return $this->installed_version;
    }
    
    /**
     * Sets the installed_version
     * @var int
     */
    function setInstalledVersion($arg0) {
        $this->installed_version = $arg0;
        $this->addModifiedColumn("installed_version");
        return $this;
    }
    
    /**
     * Returns the newest_version
     * @return integer
     */
    function getNewestVersion() {
        if (is_null($this->newest_version)) {
            $this->newest_version = 0;
        }
        return $this->newest_version;
    }
    
    /**
     * Sets the newest_version
     * @var integer
     */
    function setNewestVersion($arg0) {
        $this->newest_version = $arg0;
        $this->addModifiedColumn("newest_version");
        return $this;
    }
    
    /**
     * Returns the installed_package
     * @return Package
     */
    function getInstalledPackage() {
        if (is_null($this->installed_package)) {
            $this->installed_package = new Package();
        }
        return $this->installed_package;
    }
    
    /**
     * Sets the installed_package
     * @var Package
     */
    function setInstalledPackage($arg0) {
        if (is_array($arg0)) {
            $this->installed_package = new Package();
            $this->installed_package->populate($arg0);
        } else if (is_string($arg0)) {
            $this->installed_package = new Package();
            $this->installed_package->parseYumResults($arg0);
        }
        $this->addModifiedColumn("installed_package");
        return $this;
    }
    
    /**
     * Returns the newest_package
     * @return Package
     */
    function getNewestPackage() {
        if (is_null($this->newest_package)) {
            $this->newest_package = new Package();
        }
        return $this->newest_package;
    }
    
    /**
     * Sets the newest_package
     * @var Package
     */
    function setNewestPackage($arg0) {
        if (is_array($arg0)) {
            $this->newest_package = new Package();
            $this->newest_package->populate($arg0);
        } else if (is_string($arg0)) {
            $this->newest_package = new Package();
            $this->newest_package->parseYumResults($arg0);
        }
        $this->addModifiedColumn("newest_package");
        return $this;
    }
    
    /**
     * Returns the update_available
     * @return boolean
     */
    function getUpdateAvailable() {
        if (is_null($this->update_available)) {
            $this->update_available = false;
        }
        return $this->update_available;
    }
    
    /**
     * Sets the update_available
     * @var boolean
     */
    function setUpdateAvailable($arg0) {
        $this->update_available = $arg0;
        $this->addModifiedColumn("update_available");
        return $this;
    }
    
    /**
     * Checks for updates using yum
     * @return boolean
     */
    function checkForUpdates() {
        $cmd = "/usr/bin/yum clean metadata -q 2>&1";
        $yum_results = shell_exec($cmd);
        
        $cmd = "/usr/bin/yum info " . self::PACKAGE_NAME . " -q 2>&1";
        $yum_results = shell_exec($cmd);
        
        \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: YUM RESULTS: " . $yum_results);
        
        if (strpos($yum_results, "Error: No matching Packages to list") !== false) {
            throw new \Exception("Package " . self::PACKAGE_NAME . " is not installed yet.  Please install it before checking for updates");
        }
        
        if (strpos($yum_results, "Installed Packages") !== false) {
            $installed_version = \Mojavi\Util\StringTools::getStringBetween($yum_results, "Installed Packages", "Available Packages");
            $this->setInstalledPackage($installed_version);
        }
        
        if (strpos($yum_results, "Available Packages") !== false) {
            $available_version = substr($yum_results, strpos($yum_results, "Available Packages"));
            $this->setNewestPackage($available_version);
            $this->setUpdateAvailable(true);
            \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: UPDATE AVAILABLE");
        }        
    }
    
    /**
     * Checks for the update file and parses it
     * @return boolean
     */
    function startUpdate() {
        $this->setPercentComplete(10)->setUpdateMessage('Starting update')->saveProgress();
        $cmd = 'sudo ' . MO_WEBAPP_DIR . '/meta/crons/update.sh --silent';
        \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd);
        shell_exec($cmd);
    }
    
    /**
     * Checks for the update file and parses it
     * @return boolean
     */
    function clearProgress() {
        if (file_exists(self::UPDATE_FILE)) {
            if (is_writable(self::UPDATE_FILE)) {
                unlink(self::UPDATE_FILE);
                return true;
            }
        }
        return false;
    }
    
    /**
     * Checks for the update file and parses it
     * @return boolean
     */
    function checkProgress() {
        if (file_exists(self::UPDATE_FILE)) {
            $update_contents = json_decode(file_get_contents(self::UPDATE_FILE), true);
            
            if (isset($update_contents['percent_complete'])) {
                $this->setPercentComplete($update_contents['percent_complete']);
            }
            if (isset($update_contents['update_message'])) {
                $this->setUpdateMessage($update_contents['update_message']);
            }
            return true;
        } else {
            return false;
        }     
    }
    
    /**
     * Checks for the update file and parses it
     * @return boolean
     */
    function saveProgress() {
        $update_array = array('percent_complete' => $this->getPercentComplete(), 'update_message' => $this->getUpdateMessage());
        
        file_put_contents(self::UPDATE_FILE, json_encode($update_array));
        return true;
    }
       
}

class Package extends CommonForm {
    
    protected $name;
    protected $size;
    protected $description;
    protected $version;
    protected $release;
    
    /**
     * Parses the yum results and populates this object
     * @return boolean
     */
    function parseYumResults($yum_results) {
        $lines = explode("\n", $yum_results);
        $last_key = "";
        $yum_array = array();
        foreach ($lines as $line) {
            if (trim(substr($line, 0, strpos($line, ":") - 1)) != '') {
                $last_key = trim(substr($line, 0, strpos($line, ":")));
            }
            // "Name" is the first key, so we have multiple available packages, then only store the last
            // one by clearing the $yum_array whenever we see the "Name" key.
            if ($last_key == 'Name') { $yum_array = array(); }
            $value = trim(substr($line, strpos($line, ":") + 1));
            if (isset($yum_array[$last_key])) {
                $old_value = $yum_array[$last_key];
                $value = $old_value . ' ' . $value;
            }
            $yum_array[$last_key] = $value;
        }
        $this->populate($yum_array);
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
     * Returns the size
     * @return string
     */
    function getSize() {
        if (is_null($this->size)) {
            $this->size = "";
        }
        return $this->size;
    }
    
    /**
     * Sets the size
     * @var string
     */
    function setSize($arg0) {
        $this->size = $arg0;
        $this->addModifiedColumn("size");
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
     * Returns the version
     * @return string
     */
    function getVersion() {
        if (is_null($this->version)) {
            $this->version = "1.0.0";
        }
        return $this->version;
    }
    
    /**
     * Sets the version
     * @var string
     */
    function setVersion($arg0) {
        $this->version = $arg0;
        $this->addModifiedColumn("version");
        return $this;
    }
    
    /**
     * Returns the release
     * @return string
     */
    function getRelease() {
        if (is_null($this->release)) {
            $this->release = 0;
        }
        return $this->release;
    }
    
    /**
     * Sets the release
     * @var string
     */
    function setRelease($arg0) {
        $this->release = (int)$arg0;
        $this->addModifiedColumn("release");
        return $this;
    }
}