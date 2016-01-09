<?php

namespace Flux\Daemon;

abstract class BaseDaemon {

	const sleepTime = 30;

	protected $primaryThread = false;
	protected $startTime; //This will hold the start time of the daemon
	protected $lastSynctime; //This will hold the last time the daemon was synced
	protected $shouldShutdown = false; //If this is set to true the daemon won't run another iteration
	protected $name; //This will hold the class name of the daemon
	protected $pid; //This will hold the pid of the daemon

	abstract protected function action();

	/**
	 * Constructs a new daemon and sets the shutdown signals appropriately
	 */
	public function __construct()
	{
		//Capture any shutdown signals and call the setShutdown. Set Shutdown could be overriden
		pcntl_signal(SIGINT, array($this, "setShutdown"));
		pcntl_signal(SIGTERM, array($this, "setShutdown"));

		//This is the time that daemon starts up
		$this->startTime = time();
		$this->lastSynctime = time();
		$this->name = get_class($this);
		$this->pid = getmypid();
	}

	/**
	 * Starts up one child thread on this daemon
	 * @return integer
	 */
	public function runOne()
	{
		return $this->action();
	}

	/**
	 * Flags this daemon to shutdown gracefully
	 * @param integer $signo
	 * @return boolean
	 */
	public function setShutdown($signo)
	{
		$this->shouldShutdown = true;
		return false;
	}
	
	/**
	 * Returns the primaryThread
	 * @return boolean
	 */
	function getPrimaryThread() {
		if (is_null($this->primaryThread)) {
			$this->primaryThread = false;
		}
		return $this->primaryThread;
	}
	
	/**
	 * Sets the primaryThread
	 * @var boolean
	 */
	function setPrimaryThread($arg0) {
		$this->primaryThread = $arg0;
		return $this;
	}

	/**
	 * Logs a message to the output
	 * @param string $msg
	 * @param array $identifier_array
	 */
	protected function log($msg, array $identifier_array = array())
	{
		$full_msg = '';
		if ($this->getPrimaryThread()) {
			$initial_identifier_array = array($this->name . ' (P)', date('Y-m-d H:i:s'));
		} else {
			$initial_identifier_array = array($this->name . '    ', date('Y-m-d H:i:s'));
		}
		$identifier_array = array_merge($initial_identifier_array, $identifier_array);

		foreach($identifier_array AS $identifier_string) {
			$full_msg .= "[" . $identifier_string . "] ";
		}
		if (is_array($msg) || is_object($msg)) {
			$full_msg .= print_r($msg, true);
		} else {
			$full_msg .= $msg;
		}
		echo $full_msg . PHP_EOL;
	}
	
	/**
	 * Finds the number of pending records
	 * @return \Rdm\Record
	 */
	protected function updateLastRunTime() {
		$daemon = new \Flux\Daemon();
		$daemon->setClassName('\\' . get_class($this));
		$daemon->queryByClass();
		if (\MongoId::isValid($daemon->getId())) {
			$daemon->setStartTime(new \MongoDate());
			$daemon->update();
		}
		return true;
	}
	
	/**
	 * Finds the number of pending records
	 * @return \Rdm\Record
	 */
	protected function updatePendingRecordCount($pending_record_count) {
		$daemon = new \Flux\Daemon();
		$daemon->setClassName('\\' . get_class($this));
		$daemon->queryByClass();
		if (\MongoId::isValid($daemon->getId())) {
			$daemon->setPendingRecords($pending_record_count);
			$daemon->update();
		}
		return true;
	}
}
