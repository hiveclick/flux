<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\User;
use Flux\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class UserPasswordAction extends BasicAction
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
		/* @var $user Flux\User */
		$user = new User();
		$user->setId($this->getContext()->getUser()->getUserDetails()->getId());
		
		if (\MongoId::isValid($user->getId())) {
			$user->query();	
		}
		
		$this->getContext()->getRequest()->setAttribute("user", $user);
		
		return View::SUCCESS;
	}
}

?>