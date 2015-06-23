<?php
namespace Flux;

use Mojavi\Form\CommonForm;

class SplitQueueAttempt extends CommonForm {
    
    protected $split_queue;
    protected $fulfillment;
    protected $attempt_time;
    protected $request;
    protected $response;
    protected $is_error;
    protected $error_message;
    protected $response_time;
    
    protected $lead;
    
    private $attempt_index;
    
    /**
     * Returns the split_queue
     * @return SplitQueue
     */
    function getSplitQueue() {
        if (is_null($this->split_queue)) {
            $this->split_queue = new \Flux\Link\SplitQueue();
        }
        return $this->split_queue;
    }
    
    /**
     * Sets the split_queue
     * @var SplitQueue
     */
    function setSplitQueue($arg0) {
        if (is_array($arg0)) {
            $split_queue = $this->getSplitQueue();
            $split_queue->populate($arg0);
            if (\MongoId::isValid($split_queue->getSplitQueueId()) && $split_queue->getSplitQueueName() == '') {
                $split_queue->setSplitQueueName($split_queue->getSplitQueue()->getId());
            }
            $this->split_queue = $split_queue;
        } else if (is_string($arg0) && \MongoId::isValid($arg0)) {
            $split_queue = $this->getSplitQueue();
            $split_queue->setSplitQueueId($arg0);
            if (\MongoId::isValid($split_queue->getSplitQueueId()) && $split_queue->getSplitQueueName() == '') {
                $split_queue->setSplitQueueName($split_queue->getSplitQueue()->getId());
            }
            $this->split_queue = $split_queue;
        } else if ($arg0 instanceof \MongoId) {
            $split_queue = $this->getSplitQueue();
            $split_queue->setSplitQueueId((string)$arg0);
            if (\MongoId::isValid($split_queue->getSplitQueueId()) && $split_queue->getSplitQueueName() == '') {
                $split_queue->setSplitQueueName($split_queue->getSplitQueue()->getId());
            }
            $this->split_queue = $split_queue;
        } else if ($arg0 instanceof \Flux\Link\SplitQueue) {
            $split_queue = $this->getSplitQueue();
            $split_queue->populate($arg0);
            if (\MongoId::isValid($split_queue->getSplitQueueId()) && $split_queue->getSplitQueueName() == '') {
                $split_queue->setSplitQueueName($split_queue->getSplitQueue()->getId());
            }
            $this->split_queue = $split_queue;
        }
        return $this;
    }
    
    /**
     * Returns the lead
     * @return \Flux\Link\Lead
     */
    function getLead() {
        if (is_null($this->lead)) {
            $this->lead = new \Flux\Link\Lead();
        }
        return $this->lead;
    }
    
    /**
     * Sets the lead
     * @var \Flux\Link\Lead
     */
    function setLead($arg0) {
        if (is_array($arg0)) {
			$lead = $this->getLead();
			$lead->populate($arg0);
			$this->lead = $lead;
		} else if (is_string($arg0) && \MongoId::isValid($arg0)) {
			$lead = $this->getLead();
			$lead->setLeadId($arg0);
			$this->lead = $lead;
		} else if ($arg0 instanceof \MongoId) {
			$lead = $this->getLead();
			$lead->setLeadId((string)$arg0);
			$this->lead = $lead;
		}
        return $this;
    }
    
    
    
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
            if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
                $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
            }
            $this->fulfillment = $fulfillment;
        } else if (is_string($arg0) || is_int($arg0)) {
            $fulfillment = $this->getFulfillment();
            $fulfillment->setFulfillmentId($arg0);
            if (\MongoId::IsValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
                $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
            }
            $this->fulfillment = $fulfillment;
        } else if ($arg0 instanceof \MongoId) {
            $fulfillment = $this->getFulfillment();
            $fulfillment->setFulfillmentId($arg0);
            if (\MongoId::IsValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == '') {
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
     * Merges the lead with the fulfillment mapping and generates a query string
     * @return array
     */
    function mergeLead() {
        $params = array();
        if ($this->getSplitQueue()->getSplitQueueId() != "") {
    		/* @var $mapping \Flux\FulfillmentMap */
    		foreach ($this->getFulfillment()->getFulfillment()->getMapping() as $mapping) {
    			$value = $mapping->getMappedValue($this->getSplitQueue()->getSplitQueue()->getLead()->getLead());
    			if (trim($mapping->getFieldName()) == '') {
    				$params[$mapping->getDataField()->getKeyName()] = $value;
    			} else {
    				$params[$mapping->getFieldName()] = $value;
    			}
    		}
        } else {
            /* @var $mapping \Flux\FulfillmentMap */
            foreach ($this->getFulfillment()->getFulfillment()->getMapping() as $mapping) {
                $value = $mapping->getMappedValue($this->getLead()->getLead());
                if (trim($mapping->getFieldName()) == '') {
                    $params[$mapping->getDataField()->getKeyName()] = $value;
                } else {
                    $params[$mapping->getFieldName()] = $value;
                }
            }
        }
		return $params;
    }
}