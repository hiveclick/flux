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
		if ($this->getContext()->getRequest()->getMethod() == Request::POST) {
			try {
				/* @var $datafield Flux\DataField */
				$datafield = new DataField();
				$datafield->populate($_POST);
				$datafield->insert();

				$this->getContext()->getController()->redirect('/admin/data-field?_id=' . $datafield->getId());
			} catch (Exception $e) {
				$this->getErrors()->addError('error', $e->getMessage());
			}
			$this->getContext()->getRequest()->setAttribute("datafield", $datafield);
			return View::SUCCESS;
		} else {
			/* @var $datafield Flux\DataField */
			$datafield = new DataField();
			$datafield->populate($_GET);
			if ($datafield->getId() > 0) {
				$datafield->query();
			}

			$this->getContext()->getRequest()->setAttribute("datafield", $datafield);
		}
		return View::SUCCESS;
	}
}

?>