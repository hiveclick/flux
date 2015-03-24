<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Offer;
use Flux\Lead;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class SplitQueuePaneAttemptAction extends BasicAction
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
	    
		/* @var $offer Flux\SplitQueue */
		$split_queue = new \Flux\SplitQueue();
		$split_queue->populate($_REQUEST);
		$split_queue->query();
		
		$attempts = $split_queue->getAttempts();
		$index = $this->getContext()->getRequest()->getParameter('index');
		
		/* @var $offer Flux\SplitQueue */
		$split_queue_attempt = new \Flux\SplitQueueAttempt();
		
		if (isset($attempts[$index])) {
		    $split_queue_attempt->populate($attempts[$index]);
		}
		$split_queue_attempt->setAttemptIndex($index);

		$this->getContext()->getRequest()->setAttribute("split_queue_attempt", $split_queue_attempt);
		return View::SUCCESS;
	}
}

?>