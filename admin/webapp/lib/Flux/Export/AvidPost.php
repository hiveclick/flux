<?php
/**
 * AvidPost.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   7/18/16 12:44 PM
 */

namespace Flux\Export;


class AvidPost extends GenericPost
{
	/**
	 * Constructs this export
	 * @return void
	 */
	function __construct() {
		$this->setFulfillmentType(parent::FULFILLMENT_TYPE_POST);
		$this->setName('AvidAds POST Export');
		$this->setDescription('Send leads to AvidAds and records the payout based on the bounty');
	}
}