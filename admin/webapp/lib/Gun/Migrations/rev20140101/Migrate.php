<?php
namespace Gun\Migrations\rev20140101;

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
		\Gun\Campaign::ensureIndexes();
		\Gun\Client::ensureIndexes();
		\Gun\ClientExport::ensureIndexes();
		\Gun\Daemon::ensureIndexes();
		\Gun\DataField::ensureIndexes();
		\Gun\DomainGroup::ensureIndexes();
		\Gun\Export::ensureIndexes();
		\Gun\ExportQueue::ensureIndexes();
		\Gun\Flow::ensureIndexes();
		\Gun\Gender::ensureIndexes();
		\Gun\Lead::ensureIndexes();
		\Gun\LeadPage::ensureIndexes();
		\Gun\Offer::ensureIndexes();
		\Gun\OfferPage::ensureIndexes();
		\Gun\Preferences::ensureIndexes();
		\Gun\Server::ensureIndexes();
		\Gun\Split::ensureIndexes();
		\Gun\SplitQueue::ensureIndexes();
		\Gun\User::ensureIndexes();
		\Gun\Vertical::ensureIndexes();
		\Gun\Zip::ensureIndexes();
		StringTools::consoleWrite('   - Index Initialization', 'Done', StringTools::CONSOLE_COLOR_GREEN, true);
	}

	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {

	}

}