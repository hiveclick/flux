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


class AstoriaPingPost extends GenericPingPost
{
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_PING_POST);
		$this->setName('Astoria PING/POST Export');
		$this->setDescription('Send leads to Astoria Company (Solar, Tax) and records the variable payouts');
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
		$lead_split_attempt->setBounty($revenue);
		return true;
	}

}