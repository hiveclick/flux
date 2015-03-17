<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\DataField;
use Flux\Campaign;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class CampaignPaneInstructionAction extends BasicAction
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
		/* @var $offer Flux\Offer */
		$campaign = new Campaign();
		$campaign->populate($_REQUEST);
		$campaign->query();
		
		$this->getContext()->getRequest()->setAttribute("campaign", $campaign);
		
		/* @var $data_field Flux\DataField */
		$data_field = new DataField();
		$data_field->setIgnorePagination(true);
		$data_field->setSort('name');
		$data_fields = $data_field->queryAll();
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		
		return View::SUCCESS;
	}
}

?>