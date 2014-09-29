<?php
namespace Flux\Migrations\rev20140505;

use Mojavi\Migration\Migration;
use Mojavi\Util\StringTools as StringTools;

class Migrate extends Migration {

	/**
	 * Upgrades to this version
	 * @return boolean
	 */
	function up() {
		// Ensure that all the tables are built correctly
		StringTools::consoleWrite('   - Index Initialization', 'Building', StringTools::CONSOLE_COLOR_YELLOW);
		\Flux\Campaign::ensureIndexes();
		\Flux\Client::ensureIndexes();
		\Flux\ClientExport::ensureIndexes();
		\Flux\DataField::ensureIndexes();
		\Flux\DomainGroup::ensureIndexes();
		\Flux\Export::ensureIndexes();
		\Flux\ExportQueue::ensureIndexes();
		\Flux\Flow::ensureIndexes();
		\Flux\Gender::ensureIndexes();
		\Flux\Lead::ensureIndexes();
		\Flux\LeadPage::ensureIndexes();
		\Flux\Offer::ensureIndexes();
		\Flux\OfferPage::ensureIndexes();
		\Flux\Preferences::ensureIndexes();
		\Flux\Server::ensureIndexes();
		\Flux\Split::ensureIndexes();
		\Flux\SplitQueue::ensureIndexes();
		\Flux\User::ensureIndexes();
		\Flux\Vertical::ensureIndexes();
		\Flux\Zip::ensureIndexes();
		StringTools::consoleWrite('   - Index Initialization', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);

		// Prepare first client
		$client = new \Flux\Client();
		$client = $client->query(array('status' => \Flux\Client::CLIENT_STATUS_ACTIVE), false);
		if (is_null($client)) {
			// we don't have a client yet, let's create one
			echo "\n";
			while (trim($admin_name = StringTools::consolePrompt(StringTools::consoleColor('> Enter the name for the Administrative Client:', StringTools::CONSOLE_COLOR_WHITE), '')) == '') {
				echo StringTools::consoleColor('The name must be at least 1 character in length.', StringTools::CONSOLE_COLOR_RED) . "\n";
			}

			$client = new \Flux\Client();
			$client->setName($admin_name);
			$client->setStatus(\Flux\Client::CLIENT_STATUS_ACTIVE);
			$client->setClientType(\Flux\Client::CLIENT_TYPE_PRIMARY_ADMIN);
			$client->insert();
		}

		// Check if we have a first time user yet
		$user = new \Flux\User();
		$active_user = $user->query(array('status' => \Flux\User::USER_STATUS_ACTIVE), false);
		if (is_null($active_user)) {
			// we don't have a user yet, let's create one
			echo "\n";
			while (trim($admin_name = StringTools::consolePrompt(StringTools::consoleColor('> Enter the name for the Administrative User:', StringTools::CONSOLE_COLOR_WHITE), '')) == '') {
				echo StringTools::consoleColor('The name must be at least 1 character in length.', StringTools::CONSOLE_COLOR_RED) . "\n";
			}

			while (trim($admin_email = StringTools::consolePrompt(StringTools::consoleColor('> Enter the email for the Administrative User:', StringTools::CONSOLE_COLOR_WHITE), '')) == '') {
				echo StringTools::consoleColor('The email must be at least 1 character in length.', StringTools::CONSOLE_COLOR_RED) . "\n";
			}

			while (strlen(trim($admin_password = StringTools::consolePrompt(StringTools::consoleColor('> Enter the password for the Administrative User:', StringTools::CONSOLE_COLOR_WHITE), ''))) < 6) {
				echo StringTools::consoleColor('The password must be at least 6 character in length.', StringTools::CONSOLE_COLOR_RED) . "\n";
			}

			$user = new \Flux\User();
			$user->setName($admin_name);
			$user->setEmail($admin_email);
			$user->setPassword($admin_password);
			$user->setUserType(\Flux\User::USER_TYPE_ADMIN);
			$user->setStatus(\Flux\User::USER_STATUS_ACTIVE);
			$user->setClientId($client->getId());
			$user->setTimezone('America/Los_Angeles');
			$user->insert();
		}

		StringTools::consoleWrite('   - DataField Initialization', 'Building', StringTools::CONSOLE_COLOR_YELLOW);
		// Load the standard datafields
		$dataField_created_id = false;
		$dataField_partial_id = false;
		$dataField_conversion_id = false;
		$dataField_uview_id = false;
		$dataField_upartial_id = false;
		$dataField_uconverion_id = false;

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Client Name');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Client Name');
			$datafield->setKeyName('_client_name');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('');
			$datafield->setTags('internal,tracking');
			$datafield->insert();
		}
		
		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Offer Name');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Offer Name');
			$datafield->setKeyName('_offer_name');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('');
			$datafield->setTags('internal,tracking');
			$datafield->insert();
		}
		
		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Client ID');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Client ID');
			$datafield->setKeyName('_client_id');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_INTEGER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('');
			$datafield->setTags('internal,tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Offer ID');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Offer ID');
			$datafield->setKeyName('_offer_id');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_INTEGER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('');
			$datafield->setTags('internal,tracking');
			$datafield->insert();
		}
		
		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Client');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Client ID');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_CLIENT_ID);
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_ARRAY);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('');
			$datafield->setTags('internal,tracking');
			$datafield->insert();
		}
		
		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Offer');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Offer ID');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_OFFER_ID);
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_ARRAY);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('');
			$datafield->setTags('internal,tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('SubID 1');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('SubID 1');
			$datafield->setKeyName('s1');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('SubID 2');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('SubID 2');
			$datafield->setKeyName('s2');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('SubID 3');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('SubID 3');
			$datafield->setKeyName('s3');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('SubID 4');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('SubID 4');
			$datafield->setKeyName('s4');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('SubID 5');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('SubID 5');
			$datafield->setKeyName('s5');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Affiliate Unique ID');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Affiliate Unique ID');
			$datafield->setKeyName('uid');
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(true);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('First Name');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('First Name');
			$datafield->setKeyName('fn');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('first_name,fname,firstname');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Last Name');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Last Name');
			$datafield->setKeyName('ln');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('last_name,lname,lastname');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Address 1');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Address 1');
			$datafield->setKeyName('a1');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('address1,addr1,address');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Address 2');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Address 2');
			$datafield->setKeyName('a2');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('address2,addr2');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('City');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('City');
			$datafield->setKeyName('cy');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('city');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('State');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('State');
			$datafield->setKeyName('st');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STATE);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('state');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Zip');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Zip');
			$datafield->setKeyName('zi');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_ZIP);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('zip,zipcode,postal_code,postal');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Country');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Country');
			$datafield->setKeyName('ctry');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_COUNTRY);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('country');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Email');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Email');
			$datafield->setKeyName('em');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_EMAIL);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('email,email_address');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Phone');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Phone');
			$datafield->setKeyName('ph');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_PHONE);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('phone');
			$datafield->setTags('contact');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Gender');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Gender');
			$datafield->setKeyName('gd');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_GENDER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('gender');
			$datafield->setTags('demographic');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Birthdate');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Birthdate');
			$datafield->setKeyName('bd');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_BIRTHDATE);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('birth_date');
			$datafield->setTags('demographic');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Source URL');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield = new \Flux\DataField();
			$datafield->setName('Source URL');
			$datafield->setKeyName('url');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_URL);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('IP');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('IP');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_IP);
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_IP);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('ip_address,remote_addr');
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('User Agent Browser');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('User Agent Browser');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_USER_AGENT_BROWSER);
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('user_agent_browswer,browser');
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('User Agent Platform');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('User Agent Platform');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_USER_AGENT_PLATFORM);
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('user_agent_platform');
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('User Agent Version');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('User Agent Version');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_USER_AGENT_VERSION);
			$datafield->setReportGroup(true);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('user_agent_version');
			$datafield->setTags('tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('User Agent');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('User Agent');
			$datafield->setKeyName('ua');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_BROWSER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('user_agent');
			$datafield->setTags('tracking');

			// Find the browser id, platform id, and version id
			$browser_datafield = new \Flux\DataField();
			$browser_datafield->setName('User Agent Browser');
			$browser_datafield->queryByName();

			$platform_datafield = new \Flux\DataField();
			$platform_datafield->setName('User Agent Platform');
			$platform_datafield->queryByName();

			$version_datafield = new \Flux\DataField();
			$version_datafield->setName('User Agent Version');
			$version_datafield->queryByName();

			$datafield->setParameters(array(
				'browser' => $browser_datafield->getId(),
				'platform' => $platform_datafield->getId(),
				'version' => $version_datafield->getId()
			));

			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Created');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Created');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME);
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_DATETIME);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$dataField_created_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Updated');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Updated');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_EVENT_UPDATED_NAME);
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_DATETIME);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Impression');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Impression');
			$datafield->setKeyName('im');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_INTEGER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('impression');
			$datafield->setTags('events');
			$dataField_partial_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Partial');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Partial');
			$datafield->setKeyName('pa');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_INTEGER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('partial');
			$datafield->setTags('events');
			$dataField_partial_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Conversion');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Conversion');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME);
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_INTEGER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('conversion');
			$datafield->setTags('events');
			$dataField_conversion_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Upsell Impression');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Upsell Impression');
			$datafield->setKeyName('uim');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_INTEGER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('upsell_conversion');
			$datafield->setTags('events');
			$dataField_uview_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Upsell Partial');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Upsell Partial');
			$datafield->setKeyName('upa');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_INTEGER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('upsell_partial');
			$datafield->setTags('events');
			$dataField_upartial_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Upsell Conversion');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Upsell Conversion');
			$datafield->setKeyName('uco');
			$datafield->setReportGroup(false);
			$datafield->setPixelAllowed(false);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_INTEGER);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('upsell_conversion');
			$datafield->setTags('events');
			$dataField_uconverion_id = $datafield->insert();
		}

		/* @var $report_column \Flux\ReportColumn */
		$report_column = new \Flux\ReportColumn();
		$report_column->setName('Impressions');
		$report_column->queryByName();
		if (is_null($report_column) || (!is_null($report_column) && $report_column->getId() == 0)) {
			$report_column->setName('Impressions');
			$report_column->setStatus(\Flux\ReportColumn::REPORT_COLUMN_STATUS_ACTIVE);
			$report_column->setColumnType(\Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_EVENT_SUM);
			$report_column->setSumType(\Flux\ReportColumn::COLUMN_SUM_VALUE);
			$report_column->setFormatType(\Flux\ReportColumn::COLUMN_FORMAT_DEFAULT);

			/* @var $created_datafield \Flux\DataField */
			$created_datafield = new \Flux\DataField();
			$created_datafield->setName('Impression');
			$created_datafield->queryByName();

			$report_column->setParameters(array($created_datafield->getId()));
			$report_column->insert();
		}

		/* @var $report_column \Flux\ReportColumn */
		$report_column = new \Flux\ReportColumn();
		$report_column->setName('Records');
		$report_column->queryByName();
		if (is_null($report_column) || (!is_null($report_column) && $report_column->getId() == 0)) {
			$report_column->setName('Records');
			$report_column->setStatus(\Flux\ReportColumn::REPORT_COLUMN_STATUS_ACTIVE);
			$report_column->setColumnType(\Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_EVENT_SUM);
			$report_column->setSumType(\Flux\ReportColumn::COLUMN_SUM_VALUE);
			$report_column->setFormatType(\Flux\ReportColumn::COLUMN_FORMAT_DEFAULT);

			/* @var $created_datafield \Flux\DataField */
			$created_datafield = new \Flux\DataField();
			$created_datafield->setName('Created');
			$created_datafield->queryByName();

			$report_column->setParameters(array($created_datafield->getId()));
			$report_column->insert();
		}

		/* @var $report_column \Flux\ReportColumn */
		$report_column = new \Flux\ReportColumn();
		$report_column->setName('Partials');
		$report_column->queryByName();
		if (is_null($report_column) || (!is_null($report_column) && $report_column->getId() == 0)) {
			$report_column->setName('Partials');
			$report_column->setStatus(\Flux\ReportColumn::REPORT_COLUMN_STATUS_ACTIVE);
			$report_column->setColumnType(\Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_EVENT_SUM);
			$report_column->setSumType(\Flux\ReportColumn::COLUMN_SUM_VALUE);
			$report_column->setFormatType(\Flux\ReportColumn::COLUMN_FORMAT_DEFAULT);

			/* @var $partial_datafield \Flux\DataField */
			$partial_datafield = new \Flux\DataField();
			$partial_datafield->setName('Partial');
			$partial_datafield->queryByName();

			$report_column->setParameters(array($partial_datafield->getId()));
			$report_column->insert();
		}

		/* @var $report_column \Flux\ReportColumn */
		$report_column = new \Flux\ReportColumn();
		$report_column->setName('Conversions');
		$report_column->queryByName();
		if (is_null($report_column) || (!is_null($report_column) && $report_column->getId() == 0)) {
			$report_column->setName('Conversions');
			$report_column->setStatus(\Flux\ReportColumn::REPORT_COLUMN_STATUS_ACTIVE);
			$report_column->setColumnType(\Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_EVENT_SUM);
			$report_column->setSumType(\Flux\ReportColumn::COLUMN_SUM_VALUE);
			$report_column->setFormatType(\Flux\ReportColumn::COLUMN_FORMAT_DEFAULT);

			/* @var $conversion_datafield \Flux\DataField */
			$conversion_datafield = new \Flux\DataField();
			$conversion_datafield->setName('Conversion');
			$conversion_datafield->queryByName();

			$report_column->setParameters(array($conversion_datafield->getId()));
			$report_column->insert();
		}

		/* @var $report_column \Flux\ReportColumn */
		$report_column = new \Flux\ReportColumn();
		$report_column->setName('Upsell Impressions');
		$report_column->queryByName();
		if (is_null($report_column) || (!is_null($report_column) && $report_column->getId() == 0)) {
			$report_column->setName('Upsell Impressions');
			$report_column->setStatus(\Flux\ReportColumn::REPORT_COLUMN_STATUS_ACTIVE);
			$report_column->setColumnType(\Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_EVENT_SUM);
			$report_column->setSumType(\Flux\ReportColumn::COLUMN_SUM_VALUE);
			$report_column->setFormatType(\Flux\ReportColumn::COLUMN_FORMAT_DEFAULT);

			/* @var $upsell_view_datafield \Flux\DataField */
			$upsell_view_datafield = new \Flux\DataField();
			$upsell_view_datafield->setName('Upsell Impression');
			$upsell_view_datafield->queryByName();

			$report_column->setParameters(array($upsell_view_datafield->getId()));
			$report_column->insert();
		}

		/* @var $report_column \Flux\ReportColumn */
		$report_column = new \Flux\ReportColumn();
		$report_column->setName('Upsell Partials');
		$report_column->queryByName();
		if (is_null($report_column) || (!is_null($report_column) && $report_column->getId() == 0)) {
			$report_column->setName('Upsell Partials');
			$report_column->setStatus(\Flux\ReportColumn::REPORT_COLUMN_STATUS_ACTIVE);
			$report_column->setColumnType(\Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_EVENT_SUM);
			$report_column->setSumType(\Flux\ReportColumn::COLUMN_SUM_VALUE);
			$report_column->setFormatType(\Flux\ReportColumn::COLUMN_FORMAT_DEFAULT);

			/* @var $upsell_partial_datafield \Flux\DataField */
			$upsell_partial_datafield = new \Flux\DataField();
			$upsell_partial_datafield->setName('Upsell Partial');
			$upsell_partial_datafield->queryByName();

			$report_column->setParameters(array($upsell_partial_datafield->getId()));
			$report_column->insert();
		}

		/* @var $report_column \Flux\ReportColumn */
		$report_column = new \Flux\ReportColumn();
		$report_column->setName('Upsell Conversions');
		$report_column->queryByName();
		if (is_null($report_column) || (!is_null($report_column) && $report_column->getId() == 0)) {
			$report_column->setName('Upsell Conversions');
			$report_column->setStatus(\Flux\ReportColumn::REPORT_COLUMN_STATUS_ACTIVE);
			$report_column->setColumnType(\Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_EVENT_SUM);
			$report_column->setSumType(\Flux\ReportColumn::COLUMN_SUM_VALUE);
			$report_column->setFormatType(\Flux\ReportColumn::COLUMN_FORMAT_DEFAULT);

			/* @var $upsell_conversion_datafield \Flux\DataField */
			$upsell_conversion_datafield = new \Flux\DataField();
			$upsell_conversion_datafield->setName('Upsell Conversion');
			$upsell_conversion_datafield->queryByName();

			$report_column->setParameters(array($upsell_conversion_datafield->getId()));
			$report_column->insert();
		}

		StringTools::consoleWrite('   - DataField Initialization', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);

		StringTools::consoleWrite('   - Gender Initialization', 'Building', StringTools::CONSOLE_COLOR_YELLOW);
		if (file_exists(dirname(__FILE__) . '/gender/gender.txt')) {
			// Preparing to read file
			if (($fh = fopen(dirname(__FILE__) . '/gender/gender.txt','r')) !== false) {
				$counter = 0;
				while (($data = fgetcsv($fh, 0, "\t")) !== false) {
					if (isset($data[0]) && isset($data[1])) {
						$gender = new \Flux\Gender();
						$gender->setName(utf8_encode(strtolower($data[0])));
						$gender->setGender(strtolower($data[1]));
						$gender->insert();
					}

					$counter++;
					if (($counter % 100) == 0) { StringTools::consoleWrite('   - Gender Initialization', number_format($counter, 0, null, ','), StringTools::CONSOLE_COLOR_YELLOW); }
				}

				fclose($fh);
			}
		}

		StringTools::consoleWrite('   - Gender Initialization', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);

		StringTools::consoleWrite('   - Zipcode Initialization', 'Building', StringTools::CONSOLE_COLOR_YELLOW);
		if (file_exists(dirname(__FILE__) . '/zipdb/allCountries.txt')) {
			// Preparing to read file
			if (($fh = fopen(dirname(__FILE__) . '/zipdb/allCountries.txt','r')) !== false) {
				$counter = 0;
				while (($data = fgetcsv($fh, 0, "\t")) !== false) {
					if (isset($data[11]) && isset($data[10]) && isset($data[9]) && isset($data[8]) && isset($data[7]) && isset($data[6]) && isset($data[5]) && isset($data[4]) && isset($data[3]) && isset($data[2]) && isset($data[1]) && isset($data[0])) {
						//right now we only do US and CA
						if (in_array($data[0], array('US', 'CA'))) {
							$zip = new \Flux\Zip();
							$zip->setCountryName($data[0]);
							$zip->setPostalCode($data[1]);
							$zip->setPlaceName($data[2]);
							$zip->setStateName($data[3]);
							$zip->setState($data[4]);
							$zip->setCityName($data[5]);
							$zip->setCity($data[6]);
							$zip->setCommunityName($data[7]);
							$zip->setCommunity($data[8]);
							$zip->setLatitude($data[9]);
							$zip->setLongitude($data[10]);
							$zip->setAccuracy($data[11]);
							$zip->insert();
						}
					}
					$counter++;
					if (($counter % 100) == 0) { StringTools::consoleWrite('   - Zipcode Initialization', number_format($counter, 0, null, ','), StringTools::CONSOLE_COLOR_YELLOW); }
				}
				fclose($fh);
			}
		}

		StringTools::consoleWrite('   - Zipcode Initialization', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);

		StringTools::consoleWrite('   - Building Daemons Collection', 'Building', StringTools::CONSOLE_COLOR_YELLOW);

		\Flux\Daemon::ensureIndexes();
		$daemon = new \Flux\Daemon();
		$daemon->setType('Split');
		$daemon->query(array(), false);
		if (is_null($daemon) || (!is_null($daemon) && $daemon->getId() == 0)) {
			$daemon->setName('Split');
			$daemon->setStatus(\Flux\Daemon::DAEMON_STATUS_ACTIVE);
			$daemon->setRunStatus(\Flux\Daemon::DAEMON_RUN_STATUS_INACTIVE);
			$daemon->setThreads(2);
			$daemon->setClassName('Flux\Daemon\Split');
			$daemon->setChildren(array());
			$daemon->insert();
		}

		StringTools::consoleWrite('   - Building Daemons Collection', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);

		StringTools::consoleWrite('   - Building Flux Preferences Collection', 'Building', StringTools::CONSOLE_COLOR_YELLOW);

		\Flux\Preferences::ensureIndexes();

		StringTools::consoleWrite('   - Building Flux Preferences Collection', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);
	}

	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {

	}

}