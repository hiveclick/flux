<?php
namespace Flux\Migrations\rev20140101;

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
		\Flux\Daemon::ensureIndexes();
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
	}

	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {

	}

}