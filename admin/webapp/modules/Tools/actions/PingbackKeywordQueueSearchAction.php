<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Vertical;
use Flux\DomainGroup;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class PingbackKeywordQueueSearchAction extends BasicAction
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
		/* @var $pingback_keyword_queue Flux\PingbackKeywordQueue */
		$pingback_keyword_queue = new \Flux\PingbackKeywordQueue();
		$pingback_keyword_queue->populate($_REQUEST);

		$this->getContext()->getRequest()->setAttribute("pingback_keyword_queue", $pingback_keyword_queue);
		return View::SUCCESS;
	}
}

?>