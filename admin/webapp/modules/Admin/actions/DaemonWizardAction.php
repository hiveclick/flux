<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Daemon;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class DaemonWizardAction extends BasicAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 *
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		
		/* @var $daemon Flux\Daemon */
		$daemon = new Daemon();
		$daemon->populate($_GET);
		if (\MongoId::isValid($daemon->getId())) {
			$daemon->query();	
		}
		
		$daemon_classes = array();
		if (file_exists(MO_WEBAPP_DIR . "/lib/Flux/Daemon")) {
			$files = scandir(MO_WEBAPP_DIR . "/lib/Flux/Daemon");
			foreach ($files as $file) {
				if (strpos($file, '.') === 0) { continue; }
				if (strpos($file, 'Daemon.php') === 0) { continue; }
				if (strpos($file, 'BaseDaemon.php') === 0) { continue; }
				$daemon_classes[] = '\\Flux\\Daemon\\' . str_replace(".php", "", $file);
			}
		}

		$this->getContext()->getRequest()->setAttribute("daemon", $daemon);
		$this->getContext()->getRequest()->setAttribute("daemon_classes", $daemon_classes);
		
		return View::SUCCESS;
	}
}

?>