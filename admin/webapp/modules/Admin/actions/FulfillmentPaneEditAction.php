<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FulfillmentPaneEditAction extends BasicAction
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
		/* @var $fulfillment \Flux\Fulfillment */
		$fulfillment = new \Flux\Fulfillment();
		$fulfillment->populate($_GET);
		$fulfillment->query();
		
		/* @var $client \Flux\Client */
		$client = new \Flux\Client();
		$client->setIgnorePagination(true);
		$client->setSort('name');
		$client->setSord('asc');
		$clients = $client->queryAll();
		
		// Scan for export handlers
		$export_handlers = array();
		$files = scandir(MO_LIB_DIR . "/Flux/Export/");
		foreach ($files as $file) {
			if (strpos($file, '.') === 0) { continue; }
			if (strpos($file, 'Abstract') !== false) { continue; }
			if (trim($file) == 'Generic.php') { continue; }
			if (trim($file) == '') { continue; }
			$class_name = substr($file, 0, strpos($file, '.php'));
			$full_class_name = '\\Flux\\Export\\' . $class_name;
			$class_instance = new $full_class_name();
			$export_handlers[$class_name] = $class_instance;
		}

		$this->getContext()->getRequest()->setAttribute("fulfillment", $fulfillment);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		$this->getContext()->getRequest()->setAttribute("export_handlers", $export_handlers);
		return View::SUCCESS;
	}
}

?>