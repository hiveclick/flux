<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;
/**
 * SplitQueueDebug stores the request and breaks it into the individual values
 */
class SplitQueueFormFill extends CommonForm {
	
	protected $request;
	protected $url;
	protected $params;
	
	/**
	 * Returns the request
	 * @return string
	 */
	function getRequest() {
		if (is_null($this->request)) {
			$this->request = "";
		}
		return $this->request;
	}
	
	/**
	 * Sets the request
	 * @var string
	 */
	function setRequest($arg0) {
		if (is_string($arg0)) {
			$this->request = $arg0;
			
			// Parse the parameters from the query string
			if (strpos($arg0, '?') !== false) {
				$query = parse_url($arg0, PHP_URL_QUERY);
				$params = array();
				parse_str($query, $params);
				$this->setParams($params);
			}
			
			// Parse the url from the request
			if (strpos($arg0, 'http') !== false) {
				if (strpos($arg0, '?') !== false) {
					$url = substr($arg0, 0, strpos($arg0, '?'));
					$this->setUrl($url);
				} else {
					$this->setUrl($arg0);
				}
			}
		}
		return $this;
	}
	
	/**
	 * Returns the url
	 * @return string
	 */
	function getUrl() {
		if (is_null($this->url)) {
			$this->url = "";
		}
		return $this->url;
	}
	
	/**
	 * Sets the url
	 * @var string
	 */
	function setUrl($arg0) {
		$this->url = $arg0;
		return $this;
	}
	
	/**
	 * Returns the params
	 * @return array
	 */
	function getParams() {
		if (is_null($this->params)) {
			$this->params = array();
		}
		return $this->params;
	}
	
	/**
	 * Sets the params
	 * @var array
	 */
	function setParams($arg0) {
		if (is_array($arg0)) {
			$this->params = $arg0;
		} else if (is_string($arg0)) {
			$params = array();
			parse_str($arg0, $params);
			$this->params = $params;
		}
		return $this;
	}	
}
