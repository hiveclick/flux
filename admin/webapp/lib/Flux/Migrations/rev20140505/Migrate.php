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
		$datafield->setName('Client ID');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Client ID');
			$datafield->setDescription('The client associated with this offer');
			$datafield->setKeyName('_c');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_OBJECT);
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
			$datafield->setDescription('The offer associated with this lead');
			$datafield->setKeyName('_o');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_OBJECT);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('oid,offer_id');
			$datafield->setTags('internal,tracking');
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('SubID 1');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('SubID 1');
			$datafield->setDescription('Subid used for tracking');
			$datafield->setKeyName('s1');
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
			$datafield->setDescription('Subid used for tracking');
			$datafield->setKeyName('s2');
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
			$datafield->setDescription('Subid used for tracking');
			$datafield->setKeyName('s3');
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
			$datafield->setDescription('Subid used for tracking');
			$datafield->setKeyName('s4');
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
			$datafield->setDescription('Subid used for tracking');
			$datafield->setKeyName('s5');
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
			$datafield->setDescription('Affiliate Unique ID');
			$datafield->setKeyName('uid');
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
			$datafield->setDescription('Lead first name');
			$datafield->setKeyName('fn');
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
			$datafield->setDescription('Lead last name');
			$datafield->setKeyName('ln');
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
		$datafield->setName('Address');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Address');
			$datafield->setDescription('Lead primary address');
			$datafield->setKeyName('a1');
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
			$datafield->setDescription('Lead secondary address');
			$datafield->setKeyName('a2');
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
			$datafield->setDescription('Lead primary city');
			$datafield->setKeyName('cy');
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
			$datafield->setDescription('Lead primary state');
			$datafield->setKeyName('st');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('state');
			$datafield->setTags('contact');
			$datafield->setDataFieldSet(
			    array("name" => "Alabama", "value" => "AL"),
                array("name" => "Alaska", "value" => "AK"),
                array("name" => "Arizona", "value" => "AZ"),
                array("name" => "Arkansas", "value" => "AR"),
                array("name" => "California", "value" => "CA"),
                array("name" => "Colorado", "value" => "CO"),
                array("name" => "Connecticut", "value" => "CT"),
                array("name" => "Delaware", "value" => "DE"),
                array("name" => "Florida", "value" => "FL"),
                array("name" => "Georgia", "value" => "GA"),
                array("name" => "Hawaii", "value" => "HI"),
                array("name" => "Idaho", "value" => "ID"),
                array("name" => "Illinois", "value" => "IL"),
                array("name" => "Indiana", "value" => "IN"),
                array("name" => "Iowa", "value" => "IA"),
                array("name" => "Kansas", "value" => "KS"),
                array("name" => "Kentucky", "value" => "KY"),
                array("name" => "Louisiana", "value" => "LA"),
                array("name" => "Maine", "value" => "ME"),
                array("name" => "Maryland", "value" => "MD"),
                array("name" => "Massachusetts", "value" => "MA"),
                array("name" => "Michigan", "value" => "MI"),
                array("name" => "Minnesota", "value" => "MN"),
                array("name" => "Mississippi", "value" => "MS"),
                array("name" => "Missouri", "value" => "MO"),
                array("name" => "Montana", "value" => "MT"),
                array("name" => "Nebraska", "value" => "NE"),
                array("name" => "Nevada", "value" => "NV"),
                array("name" => "New Hampshire", "value" => "NH"),
                array("name" => "New Jersey", "value" => "NJ"),
                array("name" => "New Mexico", "value" => "NM"),
                array("name" => "New York", "value" => "NY"),
                array("name" => "North Carolina", "value" => "NC"),
                array("name" => "North Dakota", "value" => "ND"),
                array("name" => "Ohio", "value" => "OH"),
                array("name" => "Oklahoma", "value" => "OK"),
                array("name" => "Oregon", "value" => "OR"),
                array("name" => "Pennsylvania", "value" => "PA"),
                array("name" => "Rhode Island", "value" => "RI"),
                array("name" => "South Carolina", "value" => "SC"),
                array("name" => "South Dakota", "value" => "SD"),
                array("name" => "Tennessee", "value" => "TN"),
                array("name" => "Texas", "value" => "TX"),
                array("name" => "Utah", "value" => "UT"),
                array("name" => "Vermont", "value" => "VT"),
                array("name" => "Virginia", "value" => "VA"),
                array("name" => "Washington", "value" => "WA"),
                array("name" => "West Virginia", "value" => "WV"),
                array("name" => "Wisconsin", "value" => "WI"),
                array("name" => "Wyoming", "value" => "WY")
			);
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Zip');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Zip');
			$datafield->setDescription('Lead primary zipcode');
			$datafield->setKeyName('zi');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
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
			$datafield->setDescription('Lead primary country');
			$datafield->setKeyName('ctry');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('country');
			$datafield->setTags('contact');
			$datafield->setDataFieldSet(
			    array("name" => "United States", "value" => "US"),
			    array("name" => "Canada", "value" => "CA"),
			    array("name" => "Mexico", "value" => "MX"),
			    array("name" => "United Kingdom", "value" => "UK"),
			    array("name" => "France", "value" => "FR"),
			    array("name" => "Germany", "value" => "GR")
			);
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Email');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Email');
			$datafield->setDescription('Lead primary email address');
			$datafield->setKeyName('em');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
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
			$datafield->setDescription('Lead primary phone number');
			$datafield->setKeyName('ph');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
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
			$datafield->setDescription('Lead\'s gender');
			$datafield->setKeyName('gd');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('gender');
			$datafield->setTags('demographic');
			$datafield->setDataFieldSet(
			    array("name" => "Male", "value" => "m"),
			    array("name" => "Female", "value" => "f"),
			    array("name" => "Unknown", "value" => "")
			);
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Source URL');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield = new \Flux\DataField();
			$datafield->setName('Source URL');
			$datafield->setDescription('Source URL of the lead (or referer)');
			$datafield->setKeyName('url');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setTags('tracking');
			$datafield->insert();
		}
		
		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Referer');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
		    $datafield = new \Flux\DataField();
		    $datafield->setName('Referer');
		    $datafield->setDescription('Referer url for the where the user came from');
		    $datafield->setKeyName('_ref');
		    $datafield->setRequestName('referer');
		    $datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
		    $datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
		    $datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
		    $datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
		    $datafield->setTags('tracking');
		    $datafield->insert();
		}
		
		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Query String');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
		    $datafield = new \Flux\DataField();
		    $datafield->setName('Query String');
		    $datafield->setDescription('Query string pulled from the $_SERVER');
		    $datafield->setKeyName('_qs');
		    $datafield->setRequestName('qs');
		    $datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
		    $datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
		    $datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
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
			$datafield->setDescription('IP Address of the lead pulled from the $_SERVER variable');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_IP);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
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
			$datafield->setDescription('User Agent Browser');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_USER_AGENT_BROWSER);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('user_agent_browser,browser');
			$datafield->setTags('tracking');
			$datafield->setDataFieldSet(
			    array("name" => "Default Browser", "value" => "Default Browser"),
                array("name" => "Safari", "value" => "Safari"),
                array("name" => "Firefox", "value" => "Firefox"),
                array("name" => "FacebookExternalHit", "value" => "FacebookExternalHit"),
                array("name" => "AdsBot Google", "value" => "AdsBot Google"),
                array("name" => "Chrome", "value" => "Chrome"),
                array("name" => "Google Bot", "value" => "Google Bot"),
                array("name" => "meanpathbot", "value" => "meanpathbot"),
                array("name" => "aiHitBot", "value" => "aiHitBot"),
                array("name" => "Ripper", "value" => "Ripper"),
                array("name" => "Silk", "value" => "Silk"),
                array("name" => "Android", "value" => "Android"),
                array("name" => "RockMelt", "value" => "RockMelt"),
                array("name" => "MJ12bot", "value" => "MJ12bot"),
                array("name" => "Qt", "value" => "Qt"),
                array("name" => "Adobe Dialog Manager", "value" => "Adobe Dialog Manager"),
                array("name" => "BingBot", "value" => "BingBot"),
                array("name" => "IEMobile", "value" => "IEMobile"),
                array("name" => "A6-Indexer", "value" => "A6-Indexer"),
                array("name" => "Twitter App", "value" => "Twitter App"),
                array("name" => "Yahoo! Slurp", "value" => "Yahoo! Slurp"),
                array("name" => "Google Search Appliance", "value" => "Google Search Appliance"),
                array("name" => "NetSeer Crawler", "value" => "NetSeer Crawler"),
                array("name" => "Internet Archive", "value" => "Internet Archive"),
                array("name" => "Baiduspider", "value" => "Baiduspider"),
                array("name" => "Facebook App", "value" => "Facebook App"),
                array("name" => "AdsBot Google-Mobile", "value" => "AdsBot Google-Mobile"),
                array("name" => "Nokia Proxy Browser", "value" => "Nokia Proxy Browser"),
                array("name" => "NetcraftSurveyAgent", "value" => "NetcraftSurveyAgent"),
                array("name" => "Download Accelerator", "value" => "Download Accelerator"),
                array("name" => "BlackBerry", "value" => "BlackBerry"),
                array("name" => "Kindle", "value" => "Kindle"),
                array("name" => "IE", "value" => "IE"),
                array("name" => "Opera Mini", "value" => "Opera Mini"),
                array("name" => "Mail.Ru", "value" => "Mail.Ru"),
                array("name" => "Kindle Fire", "value" => "Kindle Fire"),
                array("name" => "panscient.com", "value" => "panscient.com"),
                array("name" => "proximic", "value" => "proximic"),
                array("name" => "Chromium", "value" => "Chromium"),
                array("name" => "AdSense Bot", "value" => "AdSense Bot"),
                array("name" => "ContextAd Bot", "value" => "ContextAd Bot"),
                array("name" => "Google-Site-Verification", "value" => "Google-Site-Verification"),
                array("name" => "SiteExplorer", "value" => "SiteExplorer"),
                array("name" => "Opera", "value" => "Opera"),
                array("name" => "Google Wireless Transcoder", "value" => "Google Wireless Transcoder"),
                array("name" => "Blackberry Playbook Tablet", "value" => "Blackberry Playbook Tablet"),
                array("name" => "Iron", "value" => "Iron")
			);
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('User Agent Platform');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('User Agent Platform');
			$datafield->setDescription('User Agent Platform');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_USER_AGENT_PLATFORM);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('user_agent_platform');
			$datafield->setTags('tracking');
			$datafield->setDataFieldSet(
			    array("name" => "unknown", "value" => "unknown"),
                array("name" => "MacOSX", "value" => "MacOSX"),
                array("name" => "WinXP", "value" => "WinXP"),
                array("name" => "iOS", "value" => "iOS"),
                array("name" => "Win7", "value" => "Win7"),
                array("name" => "Android", "value" => "Android"),
                array("name" => "Linux", "value" => "Linux"),
                array("name" => "WinVista", "value" => "WinVista"),
                array("name" => "Win8", "value" => "Win8"),
                array("name" => "Win8.1", "value" => "Win8.1"),
                array("name" => "FirefoxOS", "value" => "FirefoxOS"),
                array("name" => "WinPhone8", "value" => "WinPhone8"),
                array("name" => "SymbianOS", "value" => "SymbianOS"),
                array("name" => "Win2000", "value" => "Win2000"),
                array("name" => "ChromeOS", "value" => "ChromeOS"),
                array("name" => "RIM OS", "value" => "RIM OS"),
                array("name" => "JAVA", "value" => "JAVA"),
                array("name" => "RIM Tablet OS", "value" => "RIM Tablet OS")
			);
			$datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('User Agent Version');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('User Agent Version');
			$datafield->setDescription('User Agent Version');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_REF_USER_AGENT_VERSION);
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
		$datafield->setName('Created');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Created');
			$datafield->setDescription('Created time of the lead');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setDataFieldSet(
			    array('name' => 'Yes', 'value' => "1"),
			    array('name' => 'No', 'value' => "0")
			);
			$dataField_created_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Impression');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Impression');
			$datafield->setDescription('Time of the pixel impression');
			$datafield->setKeyName('im');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('impression');
			$datafield->setTags('events');
			$datafield->setDataFieldSet(
			    array('name' => 'Yes', 'value' => "1"),
			    array('name' => 'No', 'value' => "0")
			);
			$dataField_partial_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Partial');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Partial');
			$datafield->setDescription('Time of the partial event');
			$datafield->setKeyName('pa');
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('partial');
			$datafield->setTags('events');
			$datafield->setDataFieldSet(
			    array('name' => 'Yes', 'value' => "1"),
			    array('name' => 'No', 'value' => "0")
			);
			$dataField_partial_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Conversion');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
			$datafield->setName('Conversion');
			$datafield->setDescription('Time of the conversion event');
			$datafield->setKeyName(\Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME);
			$datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
			$datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
			$datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
			$datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
			$datafield->setRequestName('conversion');
			$datafield->setTags('events');
			$datafield->setDataFieldSet(
			    array('name' => 'Yes', 'value' => "1"),
			    array('name' => 'No', 'value' => "0")
			);
			$dataField_conversion_id = $datafield->insert();
		}

		/* @var $datafield \Flux\DataField */
		$datafield = new \Flux\DataField();
		$datafield->setName('Fulfilled');
		$datafield->queryByName();
		if (is_null($datafield) || (!is_null($datafield) && $datafield->getId() == 0)) {
		    $datafield->setName('Fulfilled');
		    $datafield->setDescription('Time of the fulfilled event');
		    $datafield->setKeyName(\Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME);
		    $datafield->setStatus(\Flux\DataField::DATA_FIELD_STATUS_ACTIVE);
		    $datafield->setStorageType(\Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT);
		    $datafield->setType(\Flux\DataField::DATA_FIELD_TYPE_STRING);
		    $datafield->setAccessType(\Flux\DataField::DATA_FIELD_ACCESS_TYPE_PUBLIC);
		    $datafield->setRequestName('conversion');
		    $datafield->setTags('events');
		    $datafield->setDataFieldSet(
		        array('name' => 'Yes', 'value' => "1"),
		        array('name' => 'No', 'value' => "0")
		    );
		    $dataField_conversion_id = $datafield->insert();
		}

		StringTools::consoleWrite('   - DataField Initialization', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);

		StringTools::consoleWrite('   - Zipcode Initialization', 'Building', StringTools::CONSOLE_COLOR_YELLOW);
				
		if (file_exists(MO_WEBAPP_DIR . '/../../init/zipdb/US.txt')) {
			// Preparing to read file
			if (($fh = fopen(MO_WEBAPP_DIR . '/../../init/zipdb/US.txt','r')) !== false) {
				$counter = 0;
				while (($data = fgetcsv($fh, 0, "\t")) !== false) {
					if (isset($data[0]) && isset($data[1])) {
						//right now we only do US and CA
						if (in_array($data[0], array('US', 'CA'))) {
							$zip = new \Flux\Zip();
							$zip->setCountry($data[0]);
							$zip->setZipcode(isset($data[1]) ? $data[1] : '');
							$zip->setCity(isset($data[2]) ? $data[2] : '');
							$zip->setState(isset($data[3]) ? $data[3] : '');
							$zip->setStateAbbreviation(isset($data[4]) ? $data[4] : '');
							$zip->setCounty(isset($data[5]) ? $data[5] : '');
							$zip->setCountyAbbreviation(isset($data[6]) ? $data[6] : '');
							$zip->setCommunity(isset($data[7]) ? $data[7] : '');
							$zip->setCommunityAbbreviation(isset($data[8]) ? $data[8] : '');
							$zip->setLatitude(isset($data[9]) ? $data[9] : 0);
							$zip->setLongitude(isset($data[10]) ? $data[10] : 0);
							$zip->setAccuracy(isset($data[11]) ? $data[11] : 1);
							$zip->insert();
						}
					} else {
					    StringTools::consoleWrite('   - Zipcode Initialization', 'Column 0 or Column 1 not found', StringTools::CONSOLE_COLOR_RED, true);
					}
					$counter++;
					if (($counter % 100) == 0) { StringTools::consoleWrite('   - Zipcode Initialization', number_format($counter, 0, null, ','), StringTools::CONSOLE_COLOR_YELLOW); }
				}
				fclose($fh);
			} else {
			    StringTools::consoleWrite('   - Zipcode Initialization', 'Could not open file US.txt', StringTools::CONSOLE_COLOR_RED, true);
			}
		} else {
		    StringTools::consoleWrite('   - Zipcode Initialization', 'File US.txt not found', StringTools::CONSOLE_COLOR_RED, true);
		}

		StringTools::consoleWrite('   - Zipcode Initialization', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);

		StringTools::consoleWrite('   - Building Daemons Collection', 'Building', StringTools::CONSOLE_COLOR_YELLOW);

		/* @var $daemon \Flux\Daemon */
		$daemon = new \Flux\Daemon();
		$daemon->setType('Split');
		$daemon->query(array(), false);
		if (is_null($daemon) || (!is_null($daemon) && $daemon->getId() == 0)) {
			$daemon->setName('Split');
			$daemon->setStatus(\Flux\Daemon::DAEMON_STATUS_ACTIVE);
			$daemon->setRunStatus(\Flux\Daemon::DAEMON_RUN_STATUS_INACTIVE);
			$daemon->setThreads(3);
			$daemon->setClassName('Flux\Daemon\Split');
			$daemon->setChildren(array());
			$daemon->insert();
		}

		StringTools::consoleWrite('   - Building Daemons Collection', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);
	}

	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {

	}

}