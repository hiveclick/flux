<?php
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
use Mojavi\Action\BasicConsoleAction;
use Mojavi\Util\StringTools;
use Mojavi\View\View;

class ExportQueueAction extends BasicConsoleAction
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
		try {
			$export = new \Flux\Export();
			$export->populate($_REQUEST);
			$export->query();
			if (\MongoId::isValid($export->getId())) {
				$exporter = new \Flux\ExportQueueExporter();
				$exporter->setExportId($export->getId());
				$exporter->processExport();
			} else {
				throw new Exception('An export could not be found by the id');
			}
		} catch (\Exception $e) {
			echo StringTools::consoleColor($e->getMessage(), StringTools::CONSOLE_COLOR_RED) . "\n";
		}
		return View::NONE;
	}
	
}