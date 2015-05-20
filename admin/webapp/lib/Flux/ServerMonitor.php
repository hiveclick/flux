<?php
namespace Flux;

use Mojavi\Form\CommonForm;

class ServerMonitor extends CommonForm {
    
    protected $total_hdd_space;
    protected $used_hdd_space;
    protected $available_hdd_space;
    
    protected $load_current;
    protected $load_last_5min;
    protected $load_last_hour;
    
    protected $total_ram;
    protected $used_ram;
    protected $available_ram;
    
    protected $total_swap;
    protected $used_swap;
    protected $available_swap;
    
    protected $raid_status_core9;
    protected $raid_status_core16;
    protected $raid_status_core8;
    protected $raid_status_core28;
    
    protected $warnings;
    
    /**
     * Discovers metrics for the current server
     * @return \Flux\ServerMonitor
     */
    function discoverMetrics() {
        $debug = true;
        
        $this->discoverHdd($debug);
        $this->discoverRam($debug);
        $this->discoverCpu($debug);
        $this->discoverCoreRaid($debug);
        
        if ($this->getUsedHddSpace() / $this->getTotalHddSpace() > 0.8) {
            $this->addWarning('The hard drive space is over 80% full.  You should increase it soon!');
        }
        if ($this->getUsedRam() / $this->getTotalRam() > 0.8) {
            $this->addWarning('Most of the RAM is being used.  You should increase it or you will start to use swap space!');
        }
        if ($this->getUsedSwap() > 0) {
            $this->addWarning('You are using swap memory, performance will be degraded!');
        }
        if ($this->getLoadCurrent() > 5) {
            $this->addWarning('The current CPU load (' . $this->getLoadCurrent() . ') is over 5, check the server!');
        }
        if (strpos($this->getRaidStatusCore16(), 'degraded') !== false) {
            $this->addWarning('The RAID on Core16 is degraded.  Check the hard drives immediately!');
        }
        if (strpos($this->getRaidStatusCore8(), 'degraded') !== false) {
            $this->addWarning('The RAID on Core8 is degraded.  Check the hard drives immediately!');
        }
        if (strpos($this->getRaidStatusCore9(), 'degraded') !== false) {
            $this->addWarning('The RAID on Core9 is degraded.  Check the hard drives immediately!');
        }
        if (strpos($this->getRaidStatusCore28(), 'degraded') !== false) {
            $this->addWarning('The RAID on Core28 is degraded.  Check the hard drives immediately!');
        }
        
        return $this;
    }
    
    /**
     * Discovers the current hdd space
     * @return \Flux\ServerMonitor
     */
    private function discoverHdd($debug = false) {
        $cmd = 'df -h | grep "/$"';
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd); }
        $cmd_response = trim(shell_exec($cmd));
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd_response); }
        $parts = preg_split('/\s+/', $cmd_response);
        if (isset($parts[0])) {
            $this->setTotalHddSpace($parts[0]);
        }
        if (isset($parts[1])) {
            $this->setUsedHddSpace($parts[1]);
        }
        if (isset($parts[2])) {
            $this->setAvailableHddSpace($parts[2]);
        }
        return $this;
    }
    
    /**
     * Discovers the current hdd space
     * @return \Flux\ServerMonitor
     */
    private function discoverCpu($debug = false) {
        $load_avg_contents = file_get_contents('/proc/loadavg');
        $load_avg_array = explode(" ", $load_avg_contents);
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($load_avg_array, true)); }
        if (isset($load_avg_array[0])) {
            $this->setLoadCurrent($load_avg_array[0]);
        }
        if (isset($load_avg_array[1])) {
            $this->setLoadLast5min($load_avg_array[1]);
        }
        if (isset($load_avg_array[2])) {
            $this->setLoadLastHour($load_avg_array[2]);
        }
        return $this;
    }
    
    /**
     * Discovers the current ram space
     * @return \Flux\ServerMonitor
     */
    private function discoverRam($debug = false) {
        $cmd = 'cat /proc/meminfo | grep "MemTotal"';
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd); }
        $cmd_response = trim(shell_exec($cmd));
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd_response); }
        $total_mem = trim(\Mojavi\Util\StringTools::getStringBetween($cmd_response, "MemTotal:", "kB"));
        $this->setTotalRam(($total_mem * 1024));
        
        $cmd = 'cat /proc/meminfo | grep "MemFree"';
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd); }
        $cmd_response = trim(shell_exec($cmd));
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd_response); }
        $available_mem = trim(\Mojavi\Util\StringTools::getStringBetween($cmd_response, "MemFree:", "kB"));
        $this->setAvailableRam(($available_mem * 1024));
        $this->setUsedRam(($this->getTotalRam() - $this->getAvailableRam()));
        
        $cmd = 'cat /proc/meminfo | grep "SwapTotal"';
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd); }
        $cmd_response = trim(shell_exec($cmd));
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd_response); }
        $total_mem = trim(\Mojavi\Util\StringTools::getStringBetween($cmd_response, "SwapTotal:", "kB"));
        $this->setTotalSwap(($total_mem * 1024));
        
        $cmd = 'cat /proc/meminfo | grep "SwapFree"';
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd); }
        $cmd_response = trim(shell_exec($cmd));
        if ($debug) { \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd_response); }
        $available_mem = trim(\Mojavi\Util\StringTools::getStringBetween($cmd_response, "SwapFree:", "kB"));
        $this->setAvailableSwap(($available_mem * 1024));
        $this->setUsedSwap(($this->getTotalSwap() - $this->getAvailableSwap()));
        
        return $this;
    }
    
    /**
     * Discovers the current hdd space
     * @return \Flux\ServerMonitor
     */
    private function discoverCoreRaid($debug = false) {
        $this->setRaidStatusCore9($this->discoverCoreRaidByHost('core09.jojohost.net'));
        $this->setRaidStatusCore8($this->discoverCoreRaidByHost('core08.jojohost.net'));
        $this->setRaidStatusCore16($this->discoverCoreRaidByHost('core16.jojohost.net'));
        $this->setRaidStatusCore28($this->discoverCoreRaidByHost('core28.jojohost.net'));
        return $this;
    }
    
    /**
     * Discovers the current hdd space
     * @return \Flux\ServerMonitor
     */
    private function discoverCoreRaidByHost($host, $username = 'root', $password = 'XLGemt76', $debug = false) {
        $cmd = '/usr/bin/lsiutil -p 1 -a 21,1,0,0,0';
        $cmd_response = '';
        try {
            if (($ssh_session = ssh2_connect($host)) !== false) {
                if (ssh2_auth_password($ssh_session, $username, $password) !== false) {
                    if (($stream = ssh2_exec($ssh_session, $cmd, 'xterm')) !== false) {
                        stream_set_blocking($stream, true);
                        $cmd_response = stream_get_contents($stream);
                        $cmd_response = trim(\Mojavi\Util\StringTools::getStringBetween($cmd_response, 'RAID actions menu, select an option:  [1-99 or e/p/w or 0 to quit] 1', 'RAID actions menu, select an option'));
                        @fclose($stream);
                    } else {
                        throw new \Exception('Cannot execute commands on remote server ' . $host);
                    }
                } else {
                    throw new \Exception("Cannot login to " . $host . " using username root");
                }
                
                
            } else {
                throw new \Exception("Cannot access " . $host);
            }
        } catch (\Exception $e) {
            \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $e->getMessage());
            return $this;
        }
        
        return $cmd_response;
    }
    
    /**
     * Returns the total_hdd_space
     * @return integer
     */
    function getTotalHddSpace() {
        if (is_null($this->total_hdd_space)) {
            $this->total_hdd_space = 0;
        }
        return $this->total_hdd_space;
    }
    
    /**
     * Sets the total_hdd_space
     * @var integer
     */
    function setTotalHddSpace($arg0) {
        $this->total_hdd_space = (int)$arg0;
        $this->addModifiedColumn("total_hdd_space");
        return $this;
    }
    
    /**
     * Returns the used_hdd_space
     * @return integer
     */
    function getUsedHddSpace() {
        if (is_null($this->used_hdd_space)) {
            $this->used_hdd_space = 0;
        }
        return $this->used_hdd_space;
    }
    
    /**
     * Sets the used_hdd_space
     * @var integer
     */
    function setUsedHddSpace($arg0) {
        $this->used_hdd_space = (int)$arg0;
        $this->addModifiedColumn("used_hdd_space");
        return $this;
    }
    
    /**
     * Returns the available_hdd_space
     * @return integer
     */
    function getAvailableHddSpace() {
        if (is_null($this->available_hdd_space)) {
            $this->available_hdd_space = 0;
        }
        return $this->available_hdd_space;
    }
    
    /**
     * Sets the available_hdd_space
     * @var integer
     */
    function setAvailableHddSpace($arg0) {
        $this->available_hdd_space = (int)$arg0;
        $this->addModifiedColumn("available_hdd_space");
        return $this;
    }
    
    /**
     * Returns the total_ram
     * @return integer
     */
    function getTotalRam() {
        if (is_null($this->total_ram)) {
            $this->total_ram = 0;
        }
        return $this->total_ram;
    }
    
    /**
     * Sets the total_ram
     * @var integer
     */
    function setTotalRam($arg0) {
        $this->total_ram = (int)$arg0;
        $this->addModifiedColumn("total_ram");
        return $this;
    }
    
    /**
     * Returns the used_ram
     * @return integer
     */
    function getUsedRam() {
        if (is_null($this->used_ram)) {
            $this->used_ram = 0;
        }
        return $this->used_ram;
    }
    
    /**
     * Sets the used_ram
     * @var integer
     */
    function setUsedRam($arg0) {
        $this->used_ram = (int)$arg0;
        $this->addModifiedColumn("used_ram");
        return $this;
    }
    
    /**
     * Returns the available_ram
     * @return integer
     */
    function getAvailableRam() {
        if (is_null($this->available_ram)) {
            $this->available_ram = 0;
        }
        return $this->available_ram;
    }
    
    /**
     * Sets the available_ram
     * @var integer
     */
    function setAvailableRam($arg0) {
        $this->available_ram = (int)$arg0;
        $this->addModifiedColumn("available_ram");
        return $this;
    }
    
    /**
     * Returns the total_swap
     * @return integer
     */
    function getTotalSwap() {
        if (is_null($this->total_swap)) {
            $this->total_swap = 0;
        }
        return $this->total_swap;
    }
    
    /**
     * Sets the total_swap
     * @var integer
     */
    function setTotalSwap($arg0) {
        $this->total_swap = (int)$arg0;
        $this->addModifiedColumn("total_swap");
        return $this;
    }
    
    /**
     * Returns the used_swap
     * @return integer
     */
    function getUsedSwap() {
        if (is_null($this->used_swap)) {
            $this->used_swap = 0;
        }
        return $this->used_swap;
    }
    
    /**
     * Sets the used_swap
     * @var integer
     */
    function setUsedSwap($arg0) {
        $this->used_swap = (int)$arg0;
        $this->addModifiedColumn("used_swap");
        return $this;
    }
    
    /**
     * Returns the available_swap
     * @return integer
     */
    function getAvailableSwap() {
        if (is_null($this->available_swap)) {
            $this->available_swap = 0;
        }
        return $this->available_swap;
    }
    
    /**
     * Sets the available_swap
     * @var integer
     */
    function setAvailableSwap($arg0) {
        $this->available_swap = (int)$arg0;
        $this->addModifiedColumn("available_swap");
        return $this;
    }
    
    /**
     * Returns the load_current
     * @return float
     */
    function getLoadCurrent() {
        if (is_null($this->load_current)) {
            $this->load_current = 0.0;
        }
        return $this->load_current;
    }
    
    /**
     * Sets the load_current
     * @var float
     */
    function setLoadCurrent($arg0) {
        $this->load_current = (float)$arg0;
        $this->addModifiedColumn("load_current");
        return $this;
    }
    
    /**
     * Returns the load_last_5min
     * @return float
     */
    function getLoadLast5min() {
        if (is_null($this->load_last_5min)) {
            $this->load_last_5min = 0.0;
        }
        return $this->load_last_5min;
    }
    
    /**
     * Sets the load_last_5min
     * @var float
     */
    function setLoadLast5min($arg0) {
        $this->load_last_5min = (float)$arg0;
        $this->addModifiedColumn("load_last_5min");
        return $this;
    }
    
    /**
     * Returns the load_last_hour
     * @return float
     */
    function getLoadLastHour() {
        if (is_null($this->load_last_hour)) {
            $this->load_last_hour = 0.0;
        }
        return $this->load_last_hour;
    }
    
    /**
     * Sets the load_last_hour
     * @var float
     */
    function setLoadLastHour($arg0) {
        $this->load_last_hour = (float)$arg0;
        $this->addModifiedColumn("load_last_hour");
        return $this;
    }
    
    /**
     * Returns the raid_status_core9
     * @return string
     */
    function getRaidStatusCore9() {
        if (is_null($this->raid_status_core9)) {
            $this->raid_status_core9 = "";
        }
        return $this->raid_status_core9;
    }
    
    /**
     * Sets the raid_status_core9
     * @var string
     */
    function setRaidStatusCore9($arg0) {
        $this->raid_status_core9 = $arg0;
        $this->addModifiedColumn("raid_status_core9");
        return $this;
    }
    
    /**
     * Returns the raid_status_core16
     * @return string
     */
    function getRaidStatusCore16() {
        if (is_null($this->raid_status_core16)) {
            $this->raid_status_core16 = "";
        }
        return $this->raid_status_core16;
    }
    
    /**
     * Sets the raid_status_core16
     * @var string
     */
    function setRaidStatusCore16($arg0) {
        $this->raid_status_core16 = $arg0;
        $this->addModifiedColumn("raid_status_core16");
        return $this;
    }
    
    /**
     * Returns the raid_status_core8
     * @return string
     */
    function getRaidStatusCore8() {
        if (is_null($this->raid_status_core8)) {
            $this->raid_status_core8 = "";
        }
        return $this->raid_status_core8;
    }
    
    /**
     * Sets the raid_status_core8
     * @var string
     */
    function setRaidStatusCore8($arg0) {
        $this->raid_status_core8 = $arg0;
        $this->addModifiedColumn("raid_status_core8");
        return $this;
    }
    
    /**
     * Returns the raid_status_core8
     * @return string
     */
    function getRaidStatusCore28() {
        if (is_null($this->raid_status_core28)) {
            $this->raid_status_core28 = "";
        }
        return $this->raid_status_core28;
    }
    
    /**
     * Sets the raid_status_core28
     * @var string
     */
    function setRaidStatusCore28($arg0) {
        $this->raid_status_core28 = $arg0;
        $this->addModifiedColumn("raid_status_core28");
        return $this;
    }
    
    /**
     * Returns the warnings
     * @return array
     */
    function getWarnings() {
        if (is_null($this->warnings)) {
            $this->warnings = array();
        }
        return $this->warnings;
    }
    
    /**
     * Sets the warnings
     * @var array
     */
    function setWarnings($arg0) {
        if (is_array($arg0)) {
            $this->warnings = $arg0;
        } else if (is_string($arg0)) {
            $this->warnings = array($arg0);
        }
        $this->addModifiedColumn("warnings");
        return $this;
    }
    
    /**
     * Sets the warnings
     * @var array
     */
    function addWarning($arg0) {
        $warnings = $this->getWarnings();
        $warnings[] = $arg0;
        $this->setWarnings($warnings);
        return $this;       
    }
}