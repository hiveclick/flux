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
class GetNextCommentAction extends BasicAction
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
		/* @var $ubot_queue Flux\UbotQueue */
		$ubot_queue = new \Flux\UbotQueue();
		$ubot_queue->populate($_REQUEST);
		
		$criteria = array();
		$criteria['is_processed'] = false;

		if (isset($_REQUEST['script'])) {
			$criteria['ubot.filename'] = trim($_REQUEST['script']);
		}

		$ubot_queue = $ubot_queue->findAndModify($criteria, array('$set' => array('is_processed' => true, 'process_time' => new \MongoDate())), null, array('new' => true), true);
		
		$this->getContext()->getRequest()->setAttribute("ubot_queue", $ubot_queue);
		return View::SUCCESS;
	}
	
	/**
	 * Returns whether the user needs to be logged in or not
	 * @return boolean
	 */
	function isSecure() {
		return true;
	}
}

?>