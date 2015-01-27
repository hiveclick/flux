<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Split;
use Flux\Offer;
use Flux\Vertical;
use Flux\DataField;
use Flux\DomainGroup;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class SplitAction extends BasicAction
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
			/* @var $split Flux\Split */
			$split = new Split();
			$split->populate($_POST);
			$split->update();

			$this->getContext()->getRequest()->setAttribute("split", $split);
		} else {
			/* @var $split Flux\Split */
			$split = new Split();
			$split->populate($_GET);
			$split->query();

			$this->getContext()->getRequest()->setAttribute("split", $split);
		}

		/* @var $offer Flux\Offer */
		$offer = new Offer();
		$offer->setSort('name');
		$offer->setSord('ASC');
		$offer->setIgnorePagination(true);
		$offers = $offer->queryAll();

		/* @var $vertical Flux\Vertical */
		$vertical = new Vertical();
		$vertical->setSort('name');
		$vertical->setSord('ASC');
		$vertical->setIgnorePagination(true);
		$verticals = $vertical->queryAll();

		/* @var $domain_group Flux\DomainGroup */
		$domain_group = new DomainGroup();
		$domain_group->setSort('name');
		$domain_group->setSord('ASC');
		$domain_group->setIgnorePagination(true);
		$domain_groups = $domain_group->queryAll();

		/* @var $data_field Flux\DataField */
		$data_field = new DataField();
		$data_field->setSort('name');
		$data_field->setSord('ASC');
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();

		$this->getContext()->getRequest()->setAttribute("offers", $offers);
		$this->getContext()->getRequest()->setAttribute("verticals", $verticals);
		$this->getContext()->getRequest()->setAttribute("domain_groups", $domain_groups);
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);

		return View::SUCCESS;
	}
}

?>