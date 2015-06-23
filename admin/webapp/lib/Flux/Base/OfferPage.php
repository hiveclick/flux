<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class OfferPage extends MongoForm {

	protected $offer;
	protected $name;
	protected $page_name;
	protected $preview_url;
	protected $priority;
	protected $file_path;
	protected $description;
	protected $page_source;
	protected $offer_page_flows;
	
    protected $click_today;
    protected $click_yesterday;
   
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('offer_page');
		$this->setDbName('admin');
	}
	
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
	 * Returns the name
	 * @return string
	 */
	function getName() {
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}

	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		$this->name = $arg0;
		$this->addModifiedColumn('name');
		return $this;
	}

	/**
	 * Returns the preview_url
	 * @return string
	 */
	function getPreviewUrl() {
		if (is_null($this->preview_url)) {
			$this->preview_url = "";
		}
		return $this->preview_url;
	}

	/**
	 * Sets the preview_url
	 * @var string
	 */
	function setPreviewUrl($arg0) {
		$this->preview_url = $arg0;
		$this->addModifiedColumn('preview_url');
		return $this;
	}

	/**
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = "";
		}
		return $this->description;
	}

	/**
	 * Sets the description
	 * @var string
	 */
	function setDescription($arg0) {
		$this->description = $arg0;
		$this->addModifiedColumn('description');
		return $this;
	}

	/**
	 * Returns the page_source
	 * @return string
	 */
	function getPageSource() {
		if (is_null($this->page_source)) {
			$this->page_source = "";
		}
		return $this->page_source;
	}
	
	/**
	 * Sets the page_source
	 * @var string
	 */
	function setPageSource($arg0) {
		$this->page_source = $arg0;
		$this->addModifiedColumn("page_source");
		return $this;
	}
	
	/**
	 * Returns the page_name
	 * @return string
	 */
	function getPageName() {
		if (is_null($this->page_name)) {
			$this->page_name = "";
		}
		return $this->page_name;
	}

	/**
	 * Sets the page_name
	 * @var string
	 */
	function setPageName($arg0) {
		$this->page_name = $arg0;
		$this->addModifiedColumn('page_name');
		return $this;
	}

	/**
	 * Returns the priority
	 * @return integer
	 */
	function getPriority() {
		if (is_null($this->priority)) {
			$this->priority = 0;
		}
		return $this->priority;
	}

	/**
	 * Sets the priority
	 * @var integer
	 */
	function setPriority($arg0) {
		$this->priority = (int)$arg0;
		$this->addModifiedColumn('priority');
		return $this;
	}

	/**
	 * Returns the file_path
	 * @return string
	 */
	function getFilePath() {
		if (is_null($this->file_path)) {
			$this->file_path = "";
		}
		return $this->file_path;
	}

	/**
	 * Sets the file_path
	 * @var string
	 */
	function setFilePath($arg0) {
		$this->file_path = $arg0;
		$this->addModifiedColumn('file_path');
		return $this;
	}

	/**
	 * Returns the click_today
	 * @return integer
	 */
	function getClickToday() {
		if (is_null($this->click_today)) {
			$this->click_today = 0;
		}
		return $this->click_today;
	}
	
	/**
	 * Sets the click_today
	 * @var integer
	 */
	function setClickToday($arg0) {
		$this->click_today = $arg0;
		$this->addModifiedColumn("click_today");
		return $this;
	}
	
	/**
	 * Returns the click_yesterday
	 * @return integer
	 */
	function getClickYesterday() {
		if (is_null($this->click_yesterday)) {
			$this->click_yesterday = 0;
		}
		return $this->click_yesterday;
	}
	
	/**
	 * Sets the click_yesterday
	 * @var integer
	 */
	function setClickYesterday($arg0) {
		$this->click_yesterday = $arg0;
		$this->addModifiedColumn("click_yesterday");
		return $this;
	}
	
	/**
	 * Returns the offer_page_flows
	 * @return \Flux\Link\OfferPageFlow
	 */
	function getOfferPageFlows() {
		if (is_null($this->offer_page_flows)) {
			$this->offer_page_flows = array();
		}
		return $this->offer_page_flows;
	}
	
	/**
	 * Sets the offer_page_flows
	 * @var integer|array
	 */
	function setOfferPageFlows($arg0) {
		if (is_array($arg0)) {
			$this->offer_page_flows = $arg0;
			array_walk($this->offer_page_flows, function(&$value) {
				$offer_page_flow = new \Flux\OfferPageFlow();
				$offer_page_flow->populate($value);
				$value = $offer_page_flow;
			});
		}
		$this->addModifiedColumn('offer_page_flows');
		return $this;
	}
	
	/**
	 * Returns the this
	 * @return \Flux\Link\Offer
	 */
	function getOffer() {
		if (is_null($this->offer)) {
			$this->offer = new \Flux\Link\Offer();
		}
		return $this->offer;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setOffer($arg0) {
		if (is_array($arg0)) {
			$offer = $this->getOffer();
			$offer->populate($arg0);
			if (\MongoId::isValid($offer->getOfferId()) && $offer->getOfferName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		} else if (is_string($arg0)) {
			$offer = $this->getOffer();
			$offer->setOfferId($arg0);
			if (\MongoId::isValid($offer->getOfferId()) && $offer->getOfferName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
			}
			$this->offer = $offer;
		} else if ($arg0 instanceof \MongoId) {
		    $offer = $this->getOffer();
		    $offer->setOfferId($arg0);
		    if (\MongoId::isValid($offer->getOfferId()) && $offer->getOfferName() == "") {
		        $offer->setOfferName($offer->getOffer()->getName());
		    }
		    $this->offer = $offer;
		}
		$this->addModifiedColumn('offer');
		return $this;
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$offer_page = new self();
		$offer_page->getCollection()->ensureIndex(array('offer.offer_id' => 1), array('background' => true));
		return true;
	}
}