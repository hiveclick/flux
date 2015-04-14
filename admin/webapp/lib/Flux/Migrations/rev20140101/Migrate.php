<?php
namespace Flux\Migrations\rev20140101;

class Migrate extends \Mojavi\Migration\Migration {
    
	/**
	 * Upgrades to this version
	 * @return boolean
	 */
	function up() {
		// Ensure that all the tables are built correctly
		\Mojavi\Util\StringTools::consoleWrite('   - Index Initialization', 'Building', \Mojavi\Util\StringTools::CONSOLE_COLOR_YELLOW);
		\Flux\Campaign::ensureIndexes();
		\Flux\Client::ensureIndexes();
		\Flux\Fulfillment::ensureIndexes();
		\Flux\Daemon::ensureIndexes();
		\Flux\DataField::ensureIndexes();
		\Flux\DomainGroup::ensureIndexes();
		\Flux\Export::ensureIndexes();
		\Flux\ExportQueue::ensureIndexes();
		\Flux\Flow::ensureIndexes();
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
		\Mojavi\Util\StringTools::consoleWrite('   - Index Initialization', 'Done', \Mojavi\Util\StringTools::CONSOLE_COLOR_GREEN, true);
	}

	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {

	}

}