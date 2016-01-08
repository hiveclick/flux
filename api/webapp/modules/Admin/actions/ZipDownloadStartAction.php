<?php
use Mojavi\Action\BasicRestAction;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ZipDownloadStartAction extends BasicRestAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		return parent::execute();
	}

	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Vertical
	 */
	function getInputForm() {
		return new \Flux\Zip();
	}
	
	/**
	 * Executes a POST request
	 * @return \Mojavi\Form\BasicAjaxForm
	 */
	function executePost($input_form) {
		// Handle POST Requests
		$ajax_form = new \Mojavi\Form\BasicAjaxForm();
		// Download the Zip file
		$cmd = 'ps aux | grep -v "grep" | grep "ZipDownload" | awk \'{print $2}\'';
		$existing_pids = explode("\n", shell_exec($cmd));
		if (count($existing_pids) > 0) {
			// A process already exists, so kill it
			foreach ($existing_pids as $existing_pid) {
				if (intval($existing_pid) > 0) {
					posix_kill(intval($existing_pid), \SIGTERM);
				}
			}
		}
		
		$cmd = 'php ' . MO_DOCROOT_DIR . '/cron.php -m Admin -a ZipDownload > ' . MO_LOG_FOLDER . '/zip-download.log 2>&1 &';
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . "Starting bg process: " . $cmd);
		shell_exec($cmd);		

		$ajax_form->setRecord($input_form);
		
		return $ajax_form;
	}
}

?>