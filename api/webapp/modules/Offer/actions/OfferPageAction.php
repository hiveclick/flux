<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferPageAction extends BasicRestAction
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
	 * Executes a GET request
	 */
	function executeGet($input_form) {
		// Handle GET Requests
		/* @var $ajax_form \Mojavi\Form\AjaxForm */
		$ajax_form = new \Mojavi\Form\AjaxForm();
		if (\MongoId::isValid($input_form->getId())) {
			$input_form->query();
			$ajax_form->setRecord($input_form);
		} else if (trim($input_form->getPageName()) != '') {
			$offer_page = new \Flux\OfferPage();
			$offer_page->setOfferId($input_form->getOfferId());
			$offer_page->setPageName($input_form->getPageName());
			if (is_null($page_found = $offer_page->queryByPageName())) {
				// we couldn't find the page, so add it
				$offer_page = new \Flux\OfferPage();
				$offer_page->setOfferId($input_form->getOfferId());
				$offer_page->setPageName($input_form->getPageName());
				$offer_page->setPreviewUrl($input_form->getPreviewUrl());
				$offer_page->setFilePath($input_form->getFilePath());
				$offer_page->setPriority(100);
				$offer_page->setName($input_form->getPageName());
				$offer_page->insert();
			}
			$ajax_form->setRecord($offer_page);
		} else {
			$entries = $input_form->queryAll();
			$ajax_form->setEntries($entries);
			$ajax_form->setTotal($input_form->getTotal());
			$ajax_form->setPage($input_form->getPage());
			$ajax_form->setItemsPerPage($input_form->getItemsPerPage());
		}
		return $ajax_form;
	}
}

?>