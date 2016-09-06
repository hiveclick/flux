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
class UbotWizardAction extends BasicAction
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
		
		/* @var $ubot Flux\Ubot */
		$ubot = new \Flux\Ubot();
		$ubot->populate($_GET);
		if (\MongoId::isValid($ubot->getId())) {
			$ubot->query();
		}

		$this->getContext()->getRequest()->setAttribute("ubot", $ubot);
		
		return View::SUCCESS;
	}
}

?>