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
class FulfillNextLeadAction extends BasicAction
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
		
		$split_queue->setDisposition(\Flux\SplitQueue::DISPOSITION_FULFILLED);
		$split_queue->setErrorMessage('');
		$split_queue->setIsError(false);
		
		$split_queue_attempt = new \Flux\SplitQueueAttempt();
		$split_queue_attempt->setAttemptTime(new \MongoDate());
		$split_queue_attempt->setResponse($arg0);
		$split_queue_attempt->setIsError(true);
		
		$split_queue->update();
		
		$this->getContext()->getRequest()->setAttribute("split_queue", $split_queue);
		return View::SUCCESS;
	}
}

?>