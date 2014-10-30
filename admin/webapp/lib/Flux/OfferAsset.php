<?php
namespace Flux;

use Mojavi\Form\MongoForm;

class OfferAsset extends MongoForm {

    const OFFER_ASSET_TYPE_BANNER = 1;
    const OFFER_ASSET_TYPE_WALL = 2;
    const OFFER_ASSET_TYPE_PATH = 3;
    const OFFER_ASSET_TYPE_EMAIL_HTML = 4;
    const OFFER_ASSET_TYPE_EMAIL_TEXT = 5;
    
    protected $offer_id;
    protected $name;
    protected $description;
    protected $asset_type;
    
    protected $html_source;
    protected $text_source;
    protected $banner_size;
    protected $ad_title;
    protected $ad_description;
    protected $ad_link;
    protected $image_data;
    
    private $offer;
    
    /**
     * Constructs new user
     * @return void
     */
    function __construct() {
    	$this->setCollectionName('offer_asset');
    	$this->setDbName('admin');
    }
    
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
    	$this->addModifiedColumn("offer_id");
    	return $this;
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
    	$this->addModifiedColumn("name");
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
    	$this->addModifiedColumn("description");
    	return $this;
    }
    
    /**
     * Returns the asset_type
     * @return integer
     */
    function getAssetType() {
    	if (is_null($this->asset_type)) {
    		$this->asset_type = self::OFFER_ASSET_TYPE_BANNER;
    	}
    	return $this->asset_type;
    }
    
    /**
     * Sets the asset_type
     * @var integer
     */
    function setAssetType($arg0) {
    	$this->asset_type = (int)$arg0;
    	$this->addModifiedColumn("asset_type");
    	return $this;
    }
    
    /**
     * Returns the banner_size
     * @return string
     */
    function getBannerSize() {
    	if (is_null($this->banner_size)) {
    		$this->banner_size = "";
    	}
    	return $this->banner_size;
    }
    
    /**
     * Sets the banner_size
     * @var string
     */
    function setBannerSize($arg0) {
    	$this->banner_size = (int)$arg0;
    	$this->addModifiedColumn("banner_size");
    	return $this;
    }
    
    /**
     * Returns the banner width based on the banner size
     * @return integer
     */
    function getBannerWidth() {
        switch ($this->getBannerSize()) {
            case 0: // Custom Size
            	return 0;
        	case 1: // 728x90 - Leaderboard
        	    return 728;
        	case 2: // 336x280 - Large Rectangle
        	    return 336;
    	    case 3: // 300x250 - Medium Rectangle
    	    	return 300;
	    	case 4: // 240x400 - Vertical Rectangle
	    		return 240;
    		case 5: // 180x150 - Rectangle
    			return 180;
			case 6: // 300x100 - 3:1 Rectangle
				return 300;
			case 7: // 468x60 - Full Banner
				return 468;
			case 8: // 234x60 - Half Banner
				return 234;
			case 9: // 120x240 - Vertical Banner
				return 120;
			case 10: // 160x600 - Wide Skyscraper
				return 160;
			case 11: // 120x600 - Skyscraper
				return 120;
			case 12: // 720x300 - Pop-under
				return 720;
			case 13: // 125x125 - Square Button
				return 125;
			case 14: // 120x90 - Button 1
				return 120;
			case 15: // 120x60 - Button 2
				return 120;
			default: // 300x250 - Medium Rectangle
			    return 0;    
        }
    }
    
    /**
     * Returns the banner height based on the banner size
     * @return integer
     */
    function getBannerHeight() {
    switch ($this->getBannerSize()) {
            case 0: // Custom Size
            	return 0;
        	case 1: // 728x90 - Leaderboard
        	    return 90;
        	case 2: // 336x280 - Large Rectangle
        	    return 280;
    	    case 3: // 300x250 - Medium Rectangle
    	    	return 250;
	    	case 4: // 240x400 - Vertical Rectangle
	    		return 400;
    		case 5: // 180x150 - Rectangle
    			return 150;
			case 6: // 300x100 - 3:1 Rectangle
				return 100;
			case 7: // 468x60 - Full Banner
				return 60;
			case 8: // 234x60 - Half Banner
				return 60;
			case 9: // 120x240 - Vertical Banner
				return 240;
			case 10: // 160x600 - Wide Skyscraper
				return 600;
			case 11: // 120x600 - Skyscraper
				return 600;
			case 12: // 720x300 - Pop-under
				return 300;
			case 13: // 125x125 - Square Button
				return 125;
			case 14: // 120x90 - Button 1
				return 90;
			case 15: // 120x60 - Button 2
				return 60;
			default: // 300x250 - Medium Rectangle
			    return 0;    
        }
    }
    
    /**
     * Returns the ad_title
     * @return string
     */
    function getAdTitle() {
    	if (is_null($this->ad_title)) {
    		$this->ad_title = "";
    	}
    	return $this->ad_title;
    }
    
    /**
     * Sets the ad_title
     * @var string
     */
    function setAdTitle($arg0) {
    	$this->ad_title = $arg0;
    	$this->addModifiedColumn("ad_title");
    	return $this;
    }
    
    /**
     * Returns the ad_description
     * @return string
     */
    function getAdDescription() {
    	if (is_null($this->ad_description)) {
    		$this->ad_description = "";
    	}
    	return $this->ad_description;
    }
    
    /**
     * Sets the ad_description
     * @var string
     */
    function setAdDescription($arg0) {
    	$this->ad_description = $arg0;
    	$this->addModifiedColumn("ad_description");
    	return $this;
    }
    
    /**
     * Returns the ad_link
     * @return string
     */
    function getAdLink() {
    	if (is_null($this->ad_link)) {
    		$this->ad_link = "";
    	}
    	return $this->ad_link;
    }
    
    /**
     * Sets the ad_link
     * @var string
     */
    function setAdLink($arg0) {
    	$this->ad_link = $arg0;
    	$this->addModifiedColumn("ad_link");
    	return $this;
    }
    
    /**
     * Returns the html_source
     * @return string
     */
    function getHtmlSource() {
    	if (is_null($this->html_source)) {
    		$this->html_source = "";
    	}
    	return $this->html_source;
    }
    
    /**
     * Sets the html_source
     * @var string
     */
    function setHtmlSource($arg0) {
    	$this->html_source = $arg0;
    	$this->addModifiedColumn("html_source");
    	return $this;
    }
    
    /**
     * Returns the text_source
     * @return string
     */
    function getTextSource() {
    	if (is_null($this->text_source)) {
    		$this->text_source = "";
    	}
    	return $this->text_source;
    }
    
    /**
     * Sets the text_source
     * @var string
     */
    function setTextSource($arg0) {
    	$this->text_source = $arg0;
    	$this->addModifiedColumn("text_source");
    	return $this;
    }
    
    /**
     * Returns the image_data
     * @return string
     */
    function getImageData() {
    	if (is_null($this->image_data)) {
    		$this->image_data = "";
    	}
    	return $this->image_data;
    }
    
    /**
     * Sets the image_data
     * @var string
     */
    function setImageData($arg0) {
    	$this->image_data = $arg0;
    	$this->addModifiedColumn("image_data");
    	return $this;
    }
    
    /**
     * Returns the offer
     * @return \Flux\Offer
     */
    function getOffer() {
    	if (is_null($this->offer)) {
    		$this->offer = new Offer();
    		$this->offer->setId($this->getOfferId());
    		$this->offer->query();
    	}
    	return $this->offer;
    }
    
    /**
     * Queries multiple assets
     * @return array
     */
    function queryAll(array $criteria = array(), $hydrate = true) {
    	if ($this->getOfferId() > 0) {
    		$criteria['offer_id'] = (int)$this->getOfferId();
    	}
    	return parent::queryAll($criteria, $hydrate);
    }
    
}