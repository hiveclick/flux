<?php
namespace Flux\Base;

use Mojavi\Form\CommonForm;

class LeadSplitAttempt extends CommonForm {
	
	protected $fulfillment;
	protected $attempt_time;
	protected $request;
	protected $response;
	protected $is_error;
	protected $error_message;
	protected $response_time;
	protected $screenshot;
	protected $debug_screenshots;
	protected $source;
	protected $bounty;
		
	private $attempt_index;
	
	/**
	 * Returns the fulfillment
	 * @return \Flux\Link\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->fulfillment)) {
			$this->fulfillment = new \Flux\Link\Fulfillment();
		}
		return $this->fulfillment;
	}
	
	/**
	 * Sets the fulfillment
	 * @var \Flux\Link\Fulfillment
	 */
	function setFulfillment($arg0) {
		if (is_array($arg0) || $arg0 instanceof \Flux\Link\Fulfillment) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->populate($arg0);
			if (\MongoId::isValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if (is_string($arg0) || is_int($arg0)) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->setFulfillmentId($arg0);
			if (\MongoId::IsValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if ($arg0 instanceof \MongoId) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->setFulfillmentId($arg0);
			if (\MongoId::IsValid($fulfillment->getId()) && $fulfillment->getName() == '') {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		}
		return $this;
	}
	
	/**
	 * Returns the attempt_time
	 * @return \MongoDate
	 */
	function getAttemptTime() {
		if (is_null($this->attempt_time)) {
			$this->attempt_time = new \MongoDate();
		}
		return $this->attempt_time;
	}
	
	/**
	 * Sets the attempt_time
	 * @var \MongoDate
	 */
	function setAttemptTime($arg0) {
		$this->attempt_time = $arg0;
		return $this;
	}
	
	/**
	 * Returns the request
	 * @return array
	 */
	function getRequest() {
		if (is_null($this->request)) {
			$this->request = "";
		}
		return $this->request;
	}
	
	/**
	 * Sets the request
	 * @var array
	 */
	function setRequest($arg0) {
		if (is_string($arg0)) {
			$this->request = $arg0;
		} else if (is_array($arg0)) {
			$this->request = implode(", ", $arg0);
		}
		return $this;
	}
	
	/**
	 * Returns the response
	 * @return string
	 */
	function getResponse() {
		if (is_null($this->response)) {
			$this->response = "";
		}
		return $this->response;
	}
	
	/**
	 * Sets the response
	 * @var string
	 */
	function setResponse($arg0) {
		$this->response = $arg0;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getBounty()
	{
		if (is_null($this->bounty)) {
			$this->bounty = 0.00;
		}
		return $this->bounty;
	}

	/**
	 * @param mixed $bounty
	 */
	public function setBounty($bounty)
	{
		$this->bounty = (float)$bounty;
		$this->addModifiedColumn("bounty");
	}
	
	/**
	 * Returns the is_error
	 * @return boolean
	 */
	function getIsError() {
		if (is_null($this->is_error)) {
			$this->is_error = false;
		}
		return $this->is_error;
	}
	
	/**
	 * Sets the is_error
	 * @var boolean
	 */
	function setIsError($arg0) {
		$this->is_error = $arg0;
		return $this;
	}
	
	/**
	 * Returns the error_message
	 * @return string
	 */
	function getErrorMessage() {
		if (is_null($this->error_message)) {
			$this->error_message = "";
		}
		return $this->error_message;
	}
	
	/**
	 * Sets the error_message
	 * @var string
	 */
	function setErrorMessage($arg0) {
		$this->error_message = $arg0;
		return $this;
	}
	
	/**
	 * Returns the response_time
	 * @return float
	 */
	function getResponseTime() {
		if (is_null($this->response_time)) {
			$this->response_time = 0.0;
		}
		return $this->response_time;
	}
	
	/**
	 * Sets the response_time
	 * @var float
	 */
	function setResponseTime($arg0) {
		$this->response_time = (float)$arg0;
		return $this;
	}
	
	/**
	 * Returns the attempt_index
	 * @return integer
	 */
	function getAttemptIndex() {
		if (is_null($this->attempt_index)) {
			$this->attempt_index = 0;
		}
		return $this->attempt_index;
	}
	
	/**
	 * Sets the attempt_index
	 * @var integer
	 */
	function setAttemptIndex($arg0) {
		$this->attempt_index = (int)$arg0;
		return $this;
	}
	
	/**
	 * Returns the screenshot
	 * @return string
	 */
	function getScreenshot() {
		if (is_null($this->screenshot)) {
			$this->screenshot = "iVBORw0KGgoAAAANSUhEUgAAAI0AAACNCAMAAAC9gAmXAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADBQTFRFiIiIx8fHmJiYuLi4z8/P39/f7+/vkJCQ9/f3qKiooKCg19fXwMDAsLCw5+fn////g4xs7gAAAlBJREFUeNrs1ttugzAMBuCcICEH/P5vO9tJaDr1otJA2sWPtHUYSr45Toyh/3QYaKCBBhpooIEGGmiggQYaaKCBBhpooIEGGmiggQYaaKCBBhpooIEGGmi+0xwxyW0bUcjG5FODwchhx+dB5PsfVm9OcbNuXDPJcKiaQFTyDRrTuqbw6NSMcoJT0h7k62e05L3enGKQC5lE4/oDcuGfyA+p9QaNM0U1zsqpPy4NB1VDh5+afvkIq4YRydjYWX/WeO8SD3yaXU6LJL6PZEdutlduaOfc7Txdi4YnqMRkTp2yv2tSrKzZ+p2borQmeFZ6bbRZN5y9o5Llj6tuOFcx1UbZflE232g4H+en3KhKppIvzNxwokjOl9xQK9lSbV+UzVcaau5j3XQNubxoyHmp+lVjD56+Et12k2aPn9bU1GzGLhorm8Gb5jRcwkl+3aPpQ+h+0//BNw35uPe6kbOkyXvtN/wFJ9nKDZ0BGmjWo3C7ulo0L9jaN+a+N0tUI1aWUd5lTelqm/vx3ZrG6/Rq0Txa/KXpEVEkXsxT45/JTTKFx50tmnIQ2KrpEVXY/LTGNmkNs0WfUWGLZkR6bsrTGh6huqtFcweUVC2aEbGjl7/Vjb1bc45dfrTo2AdZNHFReJOezU2Vx2Y/WnSRsThVL82MqIJjj2pS1HcseamTNih1zDW0vTQz0nPjnq2bcMx3cGnRu2473N1VI0eYkb7fnKN+ltaOzgANNNBAAw000EADDTTQQAMNNNBAAw000EADDTTQQAMNNNBAAw00/+P4EWAASblwaOZqdfQAAAAASUVORK5CYII=";
		}
		return $this->screenshot;
	}
	
	/**
	 * Sets the screenshot
	 * @var string
	 */
	function setScreenshot($arg0) {
		$this->screenshot = $arg0;
		$this->addModifiedColumn("screenshot");
		return $this;
	}
	
	/**
	 * Returns the debug_screenshots
	 * @return array
	 */
	function getDebugScreenshots() {
		if (is_null($this->debug_screenshots)) {
			$this->debug_screenshots = array();
		}
		return $this->debug_screenshots;
	}
	
	/**
	 * Sets the debug_screenshots
	 * @var array
	 */
	function setDebugScreenshots($arg0) {
		$this->debug_screenshots = $arg0;
		$this->addModifiedColumn("debug_screenshots");
		return $this;
	}
	
	/**
	 * Sets the debug_screenshots
	 * @var array
	 */
	function addDebugScreenshot($screenshot, $source = '') {
		$tmp_array = $this->getDebugScreenshots();
		$tmp_array[] = array('screenshot' => $screenshot, 'source' => $source);
		$this->debug_screenshots = $tmp_array;
		$this->addModifiedColumn("debug_screenshots");
		return $this;
	}
	
	/**
	 * Returns the source
	 * @return string
	 */
	function getSource() {
		if (is_null($this->source)) {
			$this->source = "";
		}
		return $this->source;
	}
	
	/**
	 * Sets the source
	 * @var string
	 */
	function setSource($arg0) {
		$this->source = $arg0;
		$this->addModifiedColumn("source");
		return $this;
	}
}