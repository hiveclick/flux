<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class OfferPage extends CommonForm {
	
	protected $offer_page_id;
	protected $offer_page_name;
	
	private $offer_page;
	
	/**
	 * Returns the offer_page_id
	 * @return integer
	 */
	function getOfferPageId() {
		if (is_null($this->offer_page_id)) {
			$this->offer_page_id = null;
		}
		return $this->offer_page_id;
	}
	
	/**
	 * Sets the offer_page_id
	 * @var integer
	 */
	function setOfferPageId($arg0) {
        if (is_string($arg0) && \MongoId::isValid($arg0)) {
	        $this->offer_page_id = new \MongoId($arg0);
	    } else if ($arg0 instanceof \MongoId) {
	        $this->offer_page_id = $arg0;
	    }
		return $this;
	}
	
	/**
	 * Returns the offer_page_name
	 * @return string
	 */
	function getOfferPageName() {
		if (is_null($this->offer_page_name)) {
			$this->offer_page_name = "";
		}
		return $this->offer_page_name;
	}
	
	/**
	 * Sets the offer_page_name
	 * @var string
	 */
	function setOfferPageName($arg0) {
		$this->offer_page_name = $arg0;
		return $this;
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