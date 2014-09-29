<?php
namespace Flux\Export;

use Flux\Export\GenericSingleEmail;

/**
 * Export used to send leads to HML Lawyers with specific criteria
 * This export will be used for Hip leads that have the following criteria:
 *  Revision Surgery has been scheduled/performed => true
 *   - or - 
 *  Side effect => Metallosis
 *  
 * @author Mark Hobson
 */
class HipHmlEmail extends GenericSingleEmail {
	
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setClientExportType(parent::CLIENT_EXPORT_TYPE_EMAIL);
		$this->setName('Metal Hip Single Email Export');
		$this->setDescription('Send an email to the recipients with multiple leads sent in multiple emails');
	}
	
	/**
	 * Determines if this person can accept the lead or not
	 * @param \Flux\Lead $lead
	 * @return boolean
	 */
	function canReceiveLead($lead) {
		$ret_val = parent::canReceiveLead($lead);
		if ($ret_val) {
			// If the lead is qualified, then let's do some more checking
			if (isset($lead->getD()->doc_said) && strtoupper($lead->getD()->doc_said) == 'YES') {
				return true;
			}
			if (isset($lead->getD()->side_effects) && strtoupper($lead->getD()->side_effects) == 'METALLOSIS') {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array $leads
	 * @return boolean
	 */
	function send($export_queue_items) {
		return parent::send($export_queue_items);
	}
	
}