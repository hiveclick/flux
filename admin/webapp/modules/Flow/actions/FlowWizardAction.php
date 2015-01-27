<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Flow;
use Flux\Offer;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FlowWizardAction extends BasicAction
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
		if ($this->getContext()->getRequest()->getMethod() == Request::POST) {
			try {
				/* @var $flow Flux\Flow */
				$flow = new Flow();
				$flow->populate($_POST);
				$flow->insert();
				$this->getContext()->getController()->redirect('/flow/flow?_id=' . $flow->getId());
			} catch (Exception $e) {
				$this->getErrors()->addError('error', $e->getMessage());
			}
			$this->getContext()->getRequest()->setAttribute("flow", $flow);
			return View::SUCCESS;
		} else {
			/* @var $flow Flux\Flow */
			$flow = new Flow();
			$flow->populate($_GET);
			
			$this->getContext()->getRequest()->setAttribute("flow", $flow);
		}
		return View::SUCCESS;
	}
}

?>