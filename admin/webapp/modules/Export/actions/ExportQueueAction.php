<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Export;
use Flux\Client;
use Flux\ExportQueue;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ExportQueueAction extends BasicAction
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
		/* @var $export_queue Flux\ExportQueue */
		if ($this->getContext()->getRequest()->hasParameter('export_id')) {
			$export_queue = new ExportQueue($this->getContext()->getRequest()->getParameter('export_id'));
			$export_queue->populate($_GET);
			$export_queue->query();
			
			$this->getContext()->getRequest()->setAttribute("export_queue", $export_queue);
		}
		return View::SUCCESS;
	}
}

?>