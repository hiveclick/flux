<?php

namespace Gun\Daemon;

abstract class BaseDaemon {

	const sleepTime = 30;

	protected $startTime; //This will hold the start time of the daemon
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
	 * Logs a message to the output
	 * @param string $msg
	 * @param array $identifier_array
	 */
	protected function log($msg, array $identifier_array = array())
	{
		$full_msg = '';
		$initial_identifier_array = array($this->name, date('Y-m-d H:i:s'));
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
}
