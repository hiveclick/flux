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
class UserWizardAction extends BasicAction
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
		$user->populate($_GET);
		
		if ($user->getId() > 0) {
			$user->query();	
		}
		
		$client = new Client();
		$client->setSort('name');
		$client->setSord('ASC');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();
		
		$this->getContext()->getRequest()->setAttribute("user", $user);
		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		
		return View::SUCCESS;
	}
}

?>