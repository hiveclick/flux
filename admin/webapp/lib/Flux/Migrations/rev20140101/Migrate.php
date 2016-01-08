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
		
		// Build Campaign Indexes
		try {
			\Flux\Campaign::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Campaign', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Client Indexes
		try {
			\Flux\Client::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Client', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Fulfillment Indexes
		try {
			\Flux\Fulfillment::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Fulfillment', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Daemon Indexes
		try {
			\Flux\Daemon::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Daemon', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build DataField Indexes
		try {
			\Flux\DataField::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - DataField', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build DomainGroup Indexes
		try {
			\Flux\DomainGroup::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - DomainGroup', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Export Indexes
		try {
			\Flux\Export::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Export', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build ExportQueue Indexes
		try {
			\Flux\ExportQueue::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - ExportQueue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Flow Indexes
		try {
			\Flux\Flow::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Flow', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Lead Indexes
		try {
			\Flux\Lead::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Lead', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build LeadPage Indexes
		try {
			\Flux\LeadPage::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - LeadPage', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Offer Indexes
		try {
			\Flux\Offer::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Offer', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build OfferPage Indexes
		try {
			\Flux\OfferPage::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - OfferPage', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Preferences Indexes
		try {
			\Flux\Preferences::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Preferences', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Server Indexes
		try {
			\Flux\Server::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Server', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build ReportClient Indexes
		try {
			\Flux\ReportClient::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - ReportClient', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build ReportLead Indexes
		try {
			\Flux\ReportLead::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - ReportLead', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build Split Indexes
		try {
			\Flux\Split::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Split', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build SplitQueue Indexes
		try {
			\Flux\SplitQueue::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - SplitQueue', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		
		// Build User Indexes
		try {
			\Flux\User::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - User', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}

		// Build Vertical Indexes
		try {
			\Flux\Vertical::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Vertical', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}

		// Build Zip Indexes 
		try {
			\Flux\Zip::ensureIndexes();
		} catch (\Exception $e) {
			\Mojavi\Util\StringTools::consoleWrite('	 - Zip', $e->getMessage(), \Mojavi\Util\StringTools::CONSOLE_COLOR_RED, true);
		}
		\Mojavi\Util\StringTools::consoleWrite('   - Index Initialization', 'Done', \Mojavi\Util\StringTools::CONSOLE_COLOR_GREEN, true);
	}

	/**
	 * Downgrades to this version
	 * @return boolean
	 */
	function down() {

	}

}