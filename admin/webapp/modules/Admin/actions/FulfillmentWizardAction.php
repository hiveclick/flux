<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Client;
use Flux\Fulfillment;
use Flux\DataField;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class FulfillmentWizardAction extends BasicAction
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
		/* @var $fulfillment Flux\Fulfillment */
		$fulfillment = new \Flux\Fulfillment();
		$fulfillment->populate($_GET);
		if ($fulfillment->getId() > 0) {
			$fulfillment->query();
		} else {
			// Set the default mapping
			$default_mapping = array();
			$default_mapping[] = 'email';
			$default_mapping[] = 'first_name';
			$default_mapping[] = 'last_name';
			$default_mapping[] = 'address1';
			$default_mapping[] = 'city';
			$default_mapping[] = 'state';
			$default_mapping[] = 'zip';
			$default_mapping[] = 'country';
			$default_mapping[] = 'phone';
			$default_mapping[] = 'gender';
			$default_mapping[] = 'birthdate';
			$default_mapping[] = 'url';
			$default_mapping[] = 'ip';
			$default_mapping[] = '__created';
			$mapping = array();
			foreach ($default_mapping as $request_name) {
				if (isset(\Flux\DataField::retrieveActiveDataFieldsByRequestName()[$request_name])) {
					$mapping[] = array('field_name' => $request_name, 'datafield_id' => \Flux\DataField::retrieveActiveDataFieldsByRequestName()[$request_name]->getId(), 'default_value' => '');
				}
			}
			//$fulfillment->setMapping($mapping);
		}
		
		/* @var $client Flux\Client */
		$client = new Client();
		$client->setSort('name');
		$client->setSord('ASC');
		$client->setIgnorePagination(true);
		$clients = $client->queryAll();
		
		// Scan for export handlers
		$export_handlers = array();
		$files = scandir(MO_LIB_DIR . "/Flux/Export/");
		foreach ($files as $file) {
			if (strpos($file, '.') === 0) { continue; }
			if (strpos($file, 'Abstract') !== false) { continue; }
			if ($file == 'Generic.php') { continue; }
			if (trim($file) == '') { continue; }
			$class_name = substr($file, 0, strpos($file, '.php'));
			$full_class_name = '\\Flux\\Export\\' . $class_name;
			$class_instance = new $full_class_name();
			$export_handlers[$class_name] = $class_instance;	
		}
		
		/* @var $data_field Flux\DataField */
		$data_field = new DataField();
		$data_field->setSort('name');
		$data_field->setSord('ASC');
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();

		$this->getContext()->getRequest()->setAttribute("clients", $clients);
		$this->getContext()->getRequest()->setAttribute("fulfillment", $fulfillment);
		$this->getContext()->getRequest()->setAttribute("export_handlers", $export_handlers);
		$this->getContext()->getRequest()->setAttribute("data_fields", $data_fields);
		
		return View::SUCCESS;
	}
}

?>