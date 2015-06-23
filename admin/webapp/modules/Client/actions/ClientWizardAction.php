<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ClientWizardAction extends BasicAction
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
		/* @var $client Flux\Client */
		$client = new Client();
		$client->populate($_GET);
		if (\MongoId::isValid($client->getId())) {
			$client->query();
		}
		
		// Scan for export handlers
		$network_handlers = array();
		$files = scandir(MO_LIB_DIR . "/Flux/Network/");
		foreach ($files as $file) {
		    if (strpos($file, '.') === 0) { continue; }
		    if (strpos($file, 'Abstract') !== false) { continue; }
		    if (trim($file) == 'Generic.php') { continue; }
		    if (trim($file) == '') { continue; }
		    $class_name = substr($file, 0, strpos($file, '.php'));
		    $full_class_name = '\\Flux\\Network\\' . $class_name;
		    $class_instance = new $full_class_name();
		    $network_handlers[$class_name] = $class_instance;
		}
		
		$this->getContext()->getRequest()->setAttribute("client", $client);
		$this->getContext()->getRequest()->setAttribute("network_handlers", $network_handlers);
		
		return View::SUCCESS;
	}
}

?>