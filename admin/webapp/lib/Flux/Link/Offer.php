<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Offer extends CommonForm {
	
	protected $offer_id;
	protected $offer_name;
	
	private $offer;
	
	/**
	 * Returns the offer_id
	 * @return integer
	 */
	function getOfferId() {
		if (is_null($this->offer_id)) {
			$this->offer_id = 0;
		}
		return $this->offer_id;
	}
	
	/**
	 * Sets the offer_id
	 * @var integer
	 */
	function setOfferId($arg0) {
		$this->offer_id = (int)$arg0;
		return $this;
	}
	
	/**
	 * Returns the offer_name
	 * @return string
	 */
	function getOfferName() {
		if (is_null($this->offer_name)) {
			$this->offer_name = "";
		}
		return $this->offer_name;
	}
	
	/**
	 * Sets the offer_name
	 * @var string
	 */
	function setOfferName($arg0) {
		$this->offer_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the offer
	 * @return \Flux\Offer
	 */
	function getOffer() {
		if (is_null($this->offer)) {
			$this->offer = new \Flux\Offer();
			$this->offer->setId($this->getOfferId());
			$this->offer->query();
		}
		return $this->offer;
	}
}