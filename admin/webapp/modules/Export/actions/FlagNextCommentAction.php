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
class FlagNextCommentAction extends BasicAction
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
		if ($this->getContext()->getRequest()->getMethod() == \Mojavi\Request\Request::GET) {
			/* @var $ubot_queue Flux\UbotQueue */
			$ubot_queue = new \Flux\UbotQueue();
			$ubot_queue->populate($_GET);
			$ubot_queue->query();
			
			$this->getContext()->getRequest()->setAttribute("ubot_queue", $ubot_queue);
			return View::INPUT;
		} else if ($this->getContext()->getRequest()->getMethod() == \Mojavi\Request\Request::POST) {
			/* @var $input_form Flux\UbotQueue */
			$input_form = new \Flux\UbotQueue();
			$input_form->populate($_POST);

			/* @var $ubot_queue Flux\UbotQueue */
			$ubot_queue = new \Flux\UbotQueue();
			$ubot_queue->setId($input_form->getId());
			$ubot_queue->query();

			if (\MongoId::isValid($ubot_queue->getId())) {
				$ubot_queue->setErrorMessage($input_form->getErrorMessage());
				$ubot_queue->setIsError((trim($input_form->getErrorMessage()) != ''));
				$ubot_queue->setNextAttemptAt(new \MongoDate(strtotime('now + 1 day')));
				$ubot_queue->setIsProcessed(true);
				$ubot_queue->update();
			}
			
			$this->getContext()->getRequest()->setAttribute("ubot_queue", $ubot_queue);
			return View::SUCCESS;
		}
	}
}

?>