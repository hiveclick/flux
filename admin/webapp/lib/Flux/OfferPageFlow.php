<?php
namespace Flux;

use Mojavi\Form\MojaviForm;

class OfferPageFlow extends MojaviForm {
    
    const FILTER_TYPE_ANY = 1;
    const FILTER_TYPE_ALL = 2;
    
    const NAVIGATION_TYPE_LOCAL = 1;
    const NAVIGATION_TYPE_REMOTE = 2;
    
    protected $position;
    protected $destination_offer_page_id;
    protected $offer_page_id;
    protected $filter_type;
    protected $filter_conditions;
    protected $setters;
    protected $navigation;
    
    private $offer_id;
    private $offer_page;
    private $destination_offer_page;
    
    /**
     * Returns the position
     * @return integer
     */
    function getPosition() {
    	if (is_null($this->position)) {
    		$this->position = 1;
    	}
    	return $this->position;
    }
    
    /**
     * Sets the position
     * @var integer
     */
    function setPosition($arg0) {
    	$this->position = (int)$arg0;
    	$this->addModifiedColumn("position");
    	return $this;
    }
    
    /**
     * Returns the destination_offer_page_id
     * @return integer
     */
    function getDestinationOfferPageId() {
    	if (is_null($this->destination_offer_page_id)) {
    		$this->destination_offer_page_id = 0;
    	}
    	return $this->destination_offer_page_id;
    }
    
    /**
     * Sets the destination_offer_page_id
     * @var integer
     */
    function setDestinationOfferPageId($arg0) {
    	$this->destination_offer_page_id = $arg0;
    	$this->addModifiedColumn("destination_offer_page_id");
    	return $this;
    }
    
    /**
     * Returns the offer_page_id
     * @return integer
     */
    function getOfferPageId() {
    	if (is_null($this->offer_page_id)) {
    		$this->offer_page_id = 0;
    	}
    	return $this->offer_page_id;
    }
    
    /**
     * Sets the offer_page_id
     * @var integer
     */
    function setOfferPageId($arg0) {
    	$this->offer_page_id = $arg0;
    	$this->addModifiedColumn("offer_page_id");
    	return $this;
    }
    
    /**
     * Returns the filter_type
     * @return integer
     */
    function getFilterType() {
    	if (is_null($this->filter_type)) {
    		$this->filter_type = self::FILTER_TYPE_ALL;
    	}
    	return $this->filter_type;
    }
    
    /**
     * Sets the filter_type
     * @var integer
     */
    function setFilterType($arg0) {
    	$this->filter_type = $arg0;
    	$this->addModifiedColumn("filter_type");
    	return $this;
    }
    
    /**
     * Returns the filter_conditions
     * @return array
     */
    function getFilterConditions() {
    	if (is_null($this->filter_conditions)) {
    		$this->filter_conditions = array();
    	}
    	return $this->filter_conditions;
    }
    
    /**
     * Sets the filter_conditions
     * @var array
     */
    function setFilterConditions($arg0) {
    	$this->filter_conditions = $arg0;
    	array_walk($this->filter_conditions, function(&$value, $key) { 
    	    if (is_array($value)) { 
    	        $item = new \Flux\OfferPageFlowFilter();
    	        $item->populate($value);
    	        $value = $item;
    	    }
    	});
    	$this->addModifiedColumn("filter_conditions");
    	return $this;
    }
    
    /**
     * Returns the setters
     * @return array
     */
    function getSetters() {
    	if (is_null($this->setters)) {
    		$this->setters = array();
    	}
    	return $this->setters;
    }
    
    /**
     * Sets the setters
     * @var array
     */
    function setSetters($arg0) {
        $this->setters = $arg0;
        array_walk($this->setters, function(&$value, $key) {
        	if (is_array($value)) {
        		$item = new \Flux\OfferPageFlowSetter();
        		$item->populate($value);
        		$value = $item;
        	}
        });
    	$this->addModifiedColumn("setters");
    	return $this;
    }
    
    /**
     * Returns the navigation
     * @return array
     */
    function getNavigation() {
    	if (is_null($this->navigation)) {
    		$this->navigation = new OfferPageFlowNavigation();
    	}
    	return $this->navigation;
    }
    
    /**
     * Sets the navigation
     * @var array
     */
    function setNavigation($arg0) {
    	$tmp_array = $this->getNavigation();
    	$tmp_array->populate($arg0);
    	$this->navigation = $tmp_array;
    	$this->addModifiedColumn("navigation");
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
    
    /**
     * Returns the destination_offer_page
     * @return \Flux\OfferPage
     */
    function getDestinationOfferPage() {
    	if (is_null($this->destination_offer_page)) {
    		$this->destination_offer_page = new \Flux\OfferPage();
    		$this->destination_offer_page->setId($this->getDestinationOfferPageId());
    		$this->destination_offer_page->query();
    	}
    	return $this->destination_offer_page;
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
    
}

/**
 * Used as a subclass to store offer page flow setters
 * @author hobby
 */
class OfferPageFlowNavigation extends MojaviForm {
	
	const NAVIGATION_TYPE_LOCAL = 1;
	const NAVIGATION_TYPE_REMOTE = 2;
	
	protected $navigation_type;
	protected $destination_offer_page_id;
	protected $remote_url;
	
	private $destination_offer_page;
	
	/**
	 * Returns the navigation_type
	 * @return integer
	 */
	function getNavigationType() {
		if (is_null($this->navigation_type)) {
			$this->navigation_type = self::NAVIGATION_TYPE_LOCAL;
		}
		return $this->navigation_type;
	}
	
	/**
	 * Sets the navigation_type
	 * @var integer
	 */
	function setNavigationType($arg0) {
		$this->navigation_type = $arg0;
		$this->addModifiedColumn("navigation_type");
		return $this;
	}
	
	/**
	 * Returns the destination_offer_page_id
	 * @return integer
	 */
	function getDestinationOfferPageId() {
		if (is_null($this->destination_offer_page_id)) {
			$this->destination_offer_page_id = 0;
		}
		return $this->destination_offer_page_id;
	}
	
	/**
	 * Sets the destination_offer_page_id
	 * @var integer
	 */
	function setDestinationOfferPageId($arg0) {
		$this->destination_offer_page_id = $arg0;
		$this->addModifiedColumn("destination_offer_page_id");
		return $this;
	}
	
	/**
	 * Returns the remote_url
	 * @return string
	 */
	function getRemoteUrl() {
		if (is_null($this->remote_url)) {
			$this->remote_url = "";
		}
		return $this->remote_url;
	}
	
	/**
	 * Sets the remote_url
	 * @var string
	 */
	function setRemoteUrl($arg0) {
		$this->remote_url = $arg0;
		$this->addModifiedColumn("remote_url");
		return $this;
	}
	
	/**
	 * Returns the destination_offer_page
	 * @return \Flux\OfferPage
	 */
	function getDestinationOfferPage() {
		if (is_null($this->destination_offer_page)) {
			$this->destination_offer_page = new \Flux\OfferPage();
			$this->destination_offer_page->setId($this->getDestinationOfferPageId());
			$this->destination_offer_page->query();
		}
		return $this->destination_offer_page;
	}
}

/**
 * Used as a subclass to store offer page flow setters
 * @author hobby
 */
class OfferPageFlowSetter extends MojaviForm {
    
    const SETTER_SET = 1;
    const SETTER_INCREMENT = 2;
    const SETTER_DECREMENT = 3;
    const SETTER_SET_IF_EMPTY = 4;
    
    
    protected $data_field_id;
    protected $setter_op;
    protected $setter_value;
    
    private $data_field;
    
    /**
     * Returns the data_field_id
     * @return integer
     */
    function getDataFieldId() {
    	if (is_null($this->data_field_id)) {
    		$this->data_field_id = 0;
    	}
    	return $this->data_field_id;
    }
    
    /**
     * Sets the data_field_id
     * @var integer
     */
    function setDataFieldId($arg0) {
    	$this->data_field_id = (int)$arg0;
    	$this->addModifiedColumn("data_field_id");
    	return $this;
    }
    
    /**
     * Returns the setter_op
     * @return integer
     */
    function getSetterOp() {
    	if (is_null($this->setter_op)) {
    		$this->setter_op = self::SETTER_SET;
    	}
    	return $this->setter_op;
    }
    
    /**
     * Sets the setter_op
     * @var integer
     */
    function setSetterOp($arg0) {
    	$this->setter_op = $arg0;
    	$this->addModifiedColumn("setter_op");
    	return $this;
    }
    
    /**
     * Returns the setter_value
     * @return string
     */
    function getSetterValue() {
    	if (is_null($this->setter_value)) {
    		$this->setter_value = "";
    	}
    	return $this->setter_value;
    }
    
    /**
     * Sets the setter_value
     * @var string
     */
    function setSetterValue($arg0) {
    	$this->setter_value = $arg0;
    	$this->addModifiedColumn("setter_value");
    	return $this;
    }
    
    /**
     * Returns the data_field
     * @return \Flux\DataField
     */
    function getDataField() {
    	if (is_null($this->data_field)) {
    		$this->data_field = new \Flux\DataField();
    		$this->data_field->setId($this->getDataFieldId());
    		$this->data_field->query();
    	}
    	return $this->data_field;
    }
}

/**
 * Used as a subclass to store offer page flow filters
 * @author hobby
 */
class OfferPageFlowFilter extends MojaviForm {
    
    const FILTER_OP_CONTAINS = 1;
    const FILTER_OP_BEGINS_WITH = 2;
    const FILTER_OP_ENDS_WITH = 3;
    const FILTER_OP_IS = 4;
    const FILTER_OP_IS_NOT = 5;
    const FILTER_OP_CONTAINS_NOT = 6;
    const FILTER_OP_IS_LESS_THAN = 7;
    const FILTER_OP_IS_GREATER_THAN = 8;
    
    protected $data_field_id;
    protected $filter_op;
    protected $filter_value;
    
    private $data_field;
    
    /**
     * Returns the data_field_id
     * @return integer
     */
    function getDataFieldId() {
    	if (is_null($this->data_field_id)) {
    		$this->data_field_id = 0;
    	}
    	return $this->data_field_id;
    }
    
    /**
     * Sets the data_field_id
     * @var integer
     */
    function setDataFieldId($arg0) {
    	$this->data_field_id = (int)$arg0;
    	$this->addModifiedColumn("data_field_id");
    	return $this;
    }
    
    /**
     * Returns the filter_op
     * @return int
     */
    static function getFilterOpText($filter_op) {
    	if ($filter_op == self::FILTER_OP_CONTAINS) {
    		return "contains";
    	} else if ($filter_op == self::FILTER_OP_BEGINS_WITH) {
    		return "begins with";
    	} else if ($filter_op == self::FILTER_OP_ENDS_WITH) {
    		return "ends with";
    	} else if ($filter_op == self::FILTER_OP_IS) {
    		return "is";
    	} else if ($filter_op == self::FILTER_OP_IS_NOT) {
    		return "is not";
    	} else if ($filter_op == self::FILTER_OP_CONTAINS_NOT) {
    		return "does not contain";
    	} else if ($filter_op == self::FILTER_OP_IS_LESS_THAN) {
    		return "is <=";
    	} else if ($filter_op == self::FILTER_OP_IS_GREATER_THAN) {
    		return "is >=";
    	}
    }
    
    /**
     * Returns the filter_op
     * @return int
     */
    function getFilterOp() {
    	if (is_null($this->filter_op)) {
    		$this->filter_op = self::FILTER_OP_CONTAINS;
    	}
    	return $this->filter_op;
    }
    
    /**
     * Sets the filter_op
     * @var int
     */
    function setFilterOp($arg0) {
    	$this->filter_op = (int)$arg0;
    	$this->addModifiedColumn("filter_op");
    	return $this;
    }
    
    /**
     * Returns the filter_value
     * @return string
     */
    function getFilterValue() {
    	if (is_null($this->filter_value)) {
    		$this->filter_value = array();
    	}
    	return $this->filter_value;
    }
    
    /**
     * Sets the filter_value
     * @var string
     */
    function setFilterValue($arg0) {
        if (is_array($arg0)) {
    	   $this->filter_value = $arg0;
        } else if (is_string($arg0)) {
            if (strpos($arg0, ',') !== false) {
                $this->filter_value = explode(",", $arg0);
                array_walk($this->filter_value, function(&$value, $key) { $value = trim($value); });
            } else {
                $this->filter_value = array($arg0);
            }
        }
    	$this->addModifiedColumn("filter_value");
    	return $this;
    }
    
    /**
     * Returns the data_field
     * @return \Flux\DataField
     */
    function getDataField() {
    	if (is_null($this->data_field)) {
    		$this->data_field = new \Flux\DataField();
    		$this->data_field->setId($this->getDataFieldId());
    		$this->data_field->query();
    	}
    	return $this->data_field;
    }    
    
    /**
     * Checks the data based on the filter conditions
     * @return boolean
     */
    function checkCondition($value) {
    	if ($this->getFilterOp() == self::FILTER_OP_BEGINS_WITH) {
    		foreach ($this->getFilterValue() as $filter_val) {
    			if (strpos(trim($value), trim($filter_val)) === 0) {
    				return true;
    			}
    		}
    	} else if ($this->getFilterOp() == self::FILTER_OP_ENDS_WITH) {
    		$rev_value = strrev($value);
    		foreach ($this->getFilterValue() as $filter_val) {
    			$rev_filter_val = strrev($filter_val);
    			if (strpos(trim($rev_value), trim($rev_filter_val)) === 0) {
    				return true;
    			}
    		}
    	} else if ($this->getFilterOp() == self::FILTER_OP_CONTAINS) {
    		foreach ($this->getFilterValue() as $filter_val) {
    			if (strpos(trim($value), trim($filter_val)) !== false) {
    				return true;
    			}
    		}
    	} else if ($this->getFilterOp() == self::FILTER_OP_CONTAINS_NOT) {
    		foreach ($this->getFilterValue() as $filter_val) {
    			if (strpos(trim($value), trim($filter_val)) !== false) {
    				return false;
    			}
    		}
    		return true;
    	} else if ($this->getFilterOp() == self::FILTER_OP_IS) {
    		foreach ($this->getFilterValue() as $filter_val) {
    			if (trim($value) == trim($filter_val)) {
    				return true;
    			}
    		}
    	} else if ($this->getFilterOp() == self::FILTER_OP_IS_NOT) {
    		foreach ($this->getFilterValue() as $filter_val) {
    			if (trim($value) == trim($filter_val)) {
    				return false;
    			}
    		}
    		return true;
    	} else if ($this->getFilterOp() == self::FILTER_OP_IS_LESS_THAN) {
    		foreach ($this->getFilterValue() as $filter_val) {
    			if ((int)trim($value) <= (int)trim($filter_val)) {
    				return true;
    			}
    		}
    	} else if ($this->getFilterOp() == self::FILTER_OP_IS_GREATER_THAN) {
    		foreach ($this->getFilterValue() as $filter_val) {
    			if ((int)trim($value) >= (int)trim($filter_val)) {
    				return true;
    			}
    		}
    	}
    	return false;
    }
}