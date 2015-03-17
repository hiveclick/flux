<?php
namespace Flux\Export;

use Flux\Export;
use Mojavi\Form\MongoForm;
use Flux\Split;
use Flux\SplitPosition;
use Flux\Fulfillment;

/**
 * Abstract export class used by all export classes
 * @author Mark Hobson
 */
abstract class ExportAbstract extends MongoForm {
	
	const FULFILLMENT_TYPE_NONE = 0;
	const FULFILLMENT_TYPE_POST = 1;
	const FULFILLMENT_TYPE_FTP = 2;
	const FULFILLMENT_TYPE_EMAIL = 3;
	const FULFILLMENT_TYPE_INFUSIONSOFT = 4;
	
	protected $name;
	protected $description;
	protected $fulfillment_type;
	private $split_id;
	private $split_position_id;
	private $export_id;
	private $fulfillment_id;
	
	private $export;
	private $fulfillment;
	private $split;
	private $split_position;
	
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
	 * Returns the fulfillment_type
	 * @return integer
	 */
	function getFulfillmentType() {
		if (is_null($this->fulfillment_type)) {
			$this->fulfillment_type = self::FULFILLMENT_TYPE_NONE;
		}
		return $this->fulfillment_type;
	}
	
	/**
	 * Sets the fulfillment_type
	 * @var integer
	 */
	function setFulfillmentType($arg0) {
		$this->fulfillment_type = $arg0;
		$this->addModifiedColumn("fulfillment_type");
		return $this;
	}
	
	/**
	 * Returns the split_id
	 * @return integer
	 */
	function getSplitId() {
		if (is_null($this->split_id)) {
			$this->split_id = 0;
		}
		return $this->split_id;
	}
	
	/**
	 * Sets the split_id
	 * @var integer
	 */
	function setSplitId($arg0) {
		$this->split_id = $arg0;
		$this->addModifiedColumn("split_id");
		return $this;
	}
	
	/**
	 * Returns the split_position_id
	 * @return integer
	 */
	function getSplitPositionId() {
		if (is_null($this->split_position_id)) {
			$this->split_position_id = 0;
		}
		return $this->split_position_id;
	}
	
	/**
	 * Sets the split_position_id
	 * @var integet
	 */
	function setSplitPositionId($arg0) {
		$this->split_position_id = $arg0;
		$this->addModifiedColumn("split_position_id");
		return $this;
	}
	
	/**
	 * Returns the fulfillment_id
	 * @return integer
	 */
	function getFulfillmentId() {
		if (is_null($this->fulfillment_id)) {
			$this->fulfillment_id = 0;
		}
		return $this->fulfillment_id;
	}
	
	/**
	 * Sets the fulfillment_id
	 * @var integer
	 */
	function setFulfillmentId($arg0) {
		$this->fulfillment_id = $arg0;
		$this->addModifiedColumn("fulfillment_id");
		return $this;
	}
	
	/**
	 * Returns the export_id
	 * @return integer
	 */
	function getExportId() {
		if (is_null($this->export_id)) {
			$this->export_id = 0;
		}
		return $this->export_id;
	}
	
	/**
	 * Sets the export_id
	 * @var integer
	 */
	function setExportId($arg0) {
		$this->export_id = $arg0;
		$this->addModifiedColumn("export_id");
		return $this;
	}
	
	/**
	 * Returns the export
	 * @return \Flux\Export
	 */
	function getExport() {
		if (is_null($this->export)) {
			$this->export = new \Flux\Export();
			$this->export->setId($this->getExportId());
			$this->export->query();
		}
		return $this->export;
	}
	
	/**
	 * Returns the fulfillment
	 * @return \Flux\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->fulfillment)) {
			$this->fulfillment = new \Flux\Fulfillment();
			$this->fulfillment->setId($this->getFulfillmentId());
			$this->fulfillment->query();
		}
		return $this->fulfillment;
	}
	
	/**
	 * Returns the split
	 * @return \Flux\Split
	 */
	function getSplit() {
		if (is_null($this->split)) {
			$this->split = new \Flux\Split();
			$this->split->setId($this->getSplitId());
			$this->split->query();
		}
		return $this->split;
	}
	
	/**
	 * Returns the split_position
	 * @return \Flux\SplitPosition
	 */
	function getSplitPosition() {
		if (is_null($this->split_position)) {
			$this->split_position = new \Flux\SplitPosition();
			$this->split_position->setId($this->getSplitPositionId());
			$this->split_position->query();
		}
		return $this->split_position;
	}
	
	/**
	 * Sends the leads and returns the results
	 * @param array|MongoCursor $export_queue_items
	 * @return boolean
	 */
	abstract function send($export_queue_items);
	
	/**
	 * Determines if this person can accept the lead or not
	 * @param \Flux\Lead $lead
	 * @return boolean
	 */
	function canReceiveLead($lead) {
		// Make sure we have not met our cap
		if ($this->getSplitPosition()->getDailyCapCount() >= $this->getSplitPosition()->getCap()) { return false; }
		
		// Check if we have any required fields
		if (count($this->getSplitPosition()->getDataFieldId()) > 0) {
			foreach ($this->getSplitPosition()->getDataFieldId() as $data_field_id) {
				$data_field = new \Flux\DataField();
				$data_field->setId($data_field_id);
				$data_field->query();
				if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_DEFAULT) {
					if (!isset($lead->getD()->{$data_field->getKeyName()})) { return false; }
					if (isset($lead->getD()->{$data_field->getKeyName()}) && trim($lead->getD()->{$data_field->getKeyName()}) == '') { return false; }
				} else if ($data_field->getStorageType() == \Flux\DataField::DATA_FIELD_STORAGE_TYPE_TRACKING) {
					if (!isset($lead->getT()->{$data_field->getKeyName()})) { return false; }
					if (isset($lead->getT()->{$data_field->getKeyName()}) && trim($lead->getT()->{$data_field->getKeyName()}) == '') { return false; }
				}
				/* @TODO Add event check - possible a foreach */
			}
		}
		 
		// Check if we have any required domains
		if (count($this->getSplitPosition()->getDomainGroupId()) > 0) {
			$domains = array();
			foreach ($this->getSplitPosition()->getDomainGroupId() as $domain_group_id) {
				$domain_group = new \Flux\DomainGroup();
				$domain_group->setId($domain_group_id);
				$domain_group->query();
				foreach ($domain_group->getDomains() as $domain_name) {
					$domains[] = '@' . $domain_name;
				}
			}
			$domain_match = false;
			foreach ($domains as $domain) {
				if (strpos($lead->getD()->{\Flux\DataField::retrieveDataFieldFromName('email')->getKeyName()}, $domain) !== false) {
					$domain_match = true;
					break;
				}
			}
			if (!$domain_match) { return false; }
		}
		
		// If we get this far, then return true
		return true;
	}
	
} 