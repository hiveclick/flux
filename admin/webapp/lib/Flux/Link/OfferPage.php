<?php
namespace Flux\Link;

class OfferPage extends BasicLink {
	
	private $offer_page;
	
	/**
	 * Returns the offer_page_id
	 * @return integer
	 */
	function getOfferPageId() {
		return parent::getId();
	}
	
	/**
	 * Sets the offer_page_id
	 * @var integer
	 */
	function setOfferPageId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the offer_page_name
	 * @return string
	 */
	function getOfferPageName() {
		return parent::getName();
	}
	
	/**
	 * Sets the offer_page_name
	 * @var string
	 */
	function setOfferPageName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the offer_page
	 * @return \Flux\OfferPage
	 */
	function getOfferPage() {
		if (is_null($this->offer_page)) {
			$this->offer_page = new \Flux\OfferPage();
			$this->offer_page->setId($this->getOfferPageId());
			$this->offer_page->query();
		}
		return $this->offer_page;
	}
}