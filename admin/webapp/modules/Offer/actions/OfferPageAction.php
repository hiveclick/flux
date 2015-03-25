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
class OfferPageAction extends BasicAction
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

		/* @var $offer_page Flux\OfferPage */
		$offer_page = new \Flux\OfferPage();
		$offer_page->populate($_GET);
		$offer_page->query();
		
		/* @var $server Flux\Server */
		$server = new \Flux\Server();
		$server->setIgnorePagination(true);
		$servers = $server->queryAll();

		$this->getContext()->getRequest()->setAttribute("offer_page", $offer_page);
		$this->getContext()->getRequest()->setAttribute("servers", $servers);
		
		return View::SUCCESS;
	}
}