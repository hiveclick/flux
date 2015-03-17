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
class DataFieldWizardAction extends BasicAction
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
		$datafield->populate($_GET);
		if ($datafield->getId() > 0) {
			$datafield->query();
		}
		
		// Pull in the list of unique tag names
		$tags = $datafield->queryUniqueTagNames();

		$this->getContext()->getRequest()->setAttribute("datafield", $datafield);
		$this->getContext()->getRequest()->setAttribute("tags", $tags);
			
		return View::SUCCESS;
	}
}

?>