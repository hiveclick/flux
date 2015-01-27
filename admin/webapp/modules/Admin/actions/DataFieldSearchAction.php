<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\DataField;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class DataFieldSearchAction extends BasicAction
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
		/* @var $datafield Flux\DataField */
		$datafield = new DataField();
		$datafield->populate($_REQUEST);
		if (count($datafield->getStorageTypeArray()) == 0) {
			$datafield->setStorageTypeArray(array(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT, \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DERIVED));
		}

		$this->getContext()->getRequest()->setAttribute("datafield", $datafield);
		return View::SUCCESS;
	}
}

?>