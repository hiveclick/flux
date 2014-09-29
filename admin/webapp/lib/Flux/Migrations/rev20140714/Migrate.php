<?php
namespace Flux\Migrations\rev20140714;

use Mojavi\Migration\Migration;
use Mojavi\Util\StringTools;

class Migrate extends Migration {
	
	/**
	 * Upgrades to this version
	 * @return boolean
	 */
	function up() {
		StringTools::consoleWrite('Updating descriptions for datafields', 'Updating', StringTools::CONSOLE_COLOR_RED);
		$data_field = new \Flux\DataField();
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();
		foreach ($data_fields as $data_field) {
			StringTools::consoleWrite('Updating descriptions for datafields', $data_field->getKeyName(), StringTools::CONSOLE_COLOR_YELLOW);
			$data_field->setDescription($data_field->getName());
			$data_field->addModifiedColumn('description');
			$data_field->update();
		}
		StringTools::consoleWrite('Updating descriptions for datafields', 'Updated', StringTools::CONSOLE_COLOR_GREEN, true);
	}
	
	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {
	
	}
}
