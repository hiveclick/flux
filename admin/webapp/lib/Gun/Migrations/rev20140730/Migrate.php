<?php
namespace Gun\Migrations\rev20140730;

use Mojavi\Migration\Migration;
use Mojavi\Util\StringTools;

class Migrate extends Migration {
	
	/**
	 * Upgrades to this version
	 * @return boolean
	 */
	function up() {
		StringTools::consoleWrite('Updating tracking for datafields', 'Updating', StringTools::CONSOLE_COLOR_RED);
		$data_field = new \Gun\DataField();
		$data_field->setIgnorePagination(true);
		$data_fields = $data_field->queryAll();
		foreach ($data_fields as $data_field) {
			StringTools::consoleWrite('Updating tracking for datafields', $data_field->getKeyName(), StringTools::CONSOLE_COLOR_YELLOW);
			if ($data_field->getKeyName() == \Gun\DataField::DATA_FIELD_REF_IP) {
			    $data_field->setKeyName('_ip');
			    $data_field->setRequestName(\Gun\DataField::DATA_FIELD_REF_IP);
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == \Gun\DataField::DATA_FIELD_REF_USER_AGENT_BROWSER) {
			    $data_field->setKeyName('_uab');
			    $data_field->setRequestName(\Gun\DataField::DATA_FIELD_REF_USER_AGENT_BROWSER);
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == \Gun\DataField::DATA_FIELD_REF_USER_AGENT_PLATFORM) {
			    $data_field->setKeyName('_uap');
			    $data_field->setRequestName(\Gun\DataField::DATA_FIELD_REF_USER_AGENT_PLATFORM);
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == \Gun\DataField::DATA_FIELD_REF_USER_AGENT_VERSION) {
			    $data_field->setKeyName('_uav');
			    $data_field->setRequestName(\Gun\DataField::DATA_FIELD_REF_USER_AGENT_VERSION);
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'ua') {
			    $data_field->setKeyName('_ua');
			    $data_field->setRequestName('ua');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'url') {
			    $data_field->setKeyName('_url');
			    $data_field->setRequestName('');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'referer') {
			    $data_field->setKeyName('_ref');
			    $data_field->setRequestName('referer');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'qs') {
			    $data_field->setKeyName('_qs');
			    $data_field->setRequestName('qs');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == \Gun\DataField::DATA_FIELD_REF_OFFER_ID) {
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == \Gun\DataField::DATA_FIELD_REF_CLIENT_ID) {
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 'uid') {
			    $data_field->setKeyName('uid');
			    $data_field->setRequestName('');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 's1') {
			    $data_field->setKeyName('s1');
			    $data_field->setRequestName('');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 's2') {
			    $data_field->setKeyName('s2');
			    $data_field->setRequestName('');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 's3') {
			    $data_field->setKeyName('s3');
			    $data_field->setRequestName('');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 's4') {
			    $data_field->setKeyName('s4');
			    $data_field->setRequestName('');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			} else if ($data_field->getKeyName() == 's5') {
			    $data_field->setKeyName('s5');
			    $data_field->setRequestName('');
			    $data_field->setStorageType(\Gun\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			    $data_field->update();
			}
		}
		StringTools::consoleWrite('Updating tracking for datafields', 'Updated', StringTools::CONSOLE_COLOR_GREEN, true);
	}
	
	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {
	
	}
}
