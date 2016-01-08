<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferPageFlowAction extends BasicRestAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		return parent::execute();
	}

	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function getInputForm() {
		return new \Flux\OfferPage();
	}
	
	/**
	 * Executes a PUT request
	 */
	function executePut($input_form) {
		/* @var $offer_page_form \Flux\OfferPage */
		$offer_page_form = new \Flux\OfferPage();
		$offer_page_form->setId($input_form->getId());
		$offer_page_form->setOfferPageFlows($input_form->getOfferPageFlows());
		$offer_page_form->flushFlow();
		
		return parent::executePut($input_form);
	}
}

?>