<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\User;
use Flux\Client;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class MonitoringAction extends BasicAction
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
		/* @var $server_monitor \Flux\ServerMonitor */
		$server_monitor = new \Flux\ServerMonitor();
		$server_monitor->discoverMetrics();
		
		$this->getContext()->getRequest()->setAttribute('server_monitor', $server_monitor);
		
		return View::SUCCESS;
	}
}

?>