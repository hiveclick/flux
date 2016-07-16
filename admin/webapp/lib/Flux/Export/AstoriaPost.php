<?php
/**
 * AstoriaPingPost.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   7/18/16 12:44 PM
 */

namespace Flux\Export;


class AstoriaPost extends GenericPost
{
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_POST);
		$this->setName('Astoria POST Export');
		$this->setDescription('Send leads to Astoria Company (Harp) and records the variable payouts');
	}

	/**
	 * Record Lead payout
	 * @param \Flux\LeadSplitAttempt $lead_split_attempt
	 * @param string $response
	 * @return boolean
	 */
	function recordLeadPayout($lead_split_attempt, $response) {
		// Parse the response and set the revenue
		$revenue = \Mojavi\Util\StringTools::getStringBetween($response, "<Price>", "</Price>");
		$report_lead = new \Flux\ReportLead();
		$report_lead->setLead($lead_split_attempt->getLeadSplit()->getLeadSplit()->getLead()->getId());
		$report_lead->setClient($lead_split_attempt->getFulfillment()->getFulfillment()->getClient()->getId());
		$report_lead->setStartDate(new \MongoDate());
		$report_lead->setAccepted(true);
		$report_lead->setRevenue($revenue);
		$report_lead->setDisposition(\Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED);
		$report_lead->insert();
		return true;
	}

}