<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Campaign;
use Flux\Client;
use Flux\Offer;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class CampaignLeadsAction extends BasicAction
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
		/* @var $user Flux\Campaign */
		$campaign = new Campaign();
		$campaign->populate($_GET);
		$campaign->query();
		
		/* @var $datafield \Flux\DataField */
		$data_field = new \Flux\DataField();
		$data_field->setSort('name');
		$data_field->setSord('asc');
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();
			
		$this->getContext()->getRequest()->setAttribute("campaign", $campaign);
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		return View::SUCCESS;
	}
}

?>