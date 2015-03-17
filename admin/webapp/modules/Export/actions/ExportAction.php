<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Export;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ExportAction extends BasicAction
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
		
		/* @var $export Flux\Export */
		$export = new Export();
		$export->populate($_GET);
		$export->query();
		
		/* @var $client Flux\Client */
		$client = new Client();
		$client->setSort('name');
		$client->setSord('asc');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("export", $export);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		
		return View::SUCCESS;
	}
}

?>