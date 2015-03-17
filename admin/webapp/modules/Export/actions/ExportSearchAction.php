<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Export;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ExportSearchAction extends BasicAction
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
		$export = new \Flux\Export();
		$export->populate($_REQUEST);
		
		$split = new \Flux\Split();
		$split->setIgnorePagination(true);
		$split->setSort('name');
		$split->setSord('asc');
		$splits = $split->queryAll();
		
		$fulfillment = new \Flux\Fulfillment();
		$fulfillment->setIgnorePagination(true);
		$fulfillment->setSort('name');
		$fulfillment->setSord('asc');
		$fulfillments = $fulfillment->queryAll();
		

		$this->getContext()->getRequest()->setAttribute("export", $export);
		$this->getContext()->getRequest()->setAttribute("splits", $splits);
		$this->getContext()->getRequest()->setAttribute("fulfillments", $fulfillments);
		return View::SUCCESS;
	}
}

?>