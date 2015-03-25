<?php
namespace Flux;

class SpyReport extends BaseReport {

	protected static $columns;

	protected $last_end_time;

	public function __construct() {
		$this->setCollectionName('lead');
		$this->setDbName('lead');
	}

	/**
	 * Returns an array of columns that can be used in the spy report
	 * @return array
	 */
	public static function getColumns() {
		if(is_null(static::$columns)) {
			//@todo: create a new property for default column sort

			   foreach(\Flux\DataField::retrieveActiveDataFields() AS $dataField) {
				$datafield_selector = '';
				if($dataField->retrieveValue('storage_type') === \Flux\DataField::DATA_FIELD_STORAGE_TYPE_MAIN) {
					$datafield_selector = $dataField->getKeyName();
				} else if($dataField->retrieveValue('storage_type') === \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
					$datafield_selector = $dataField->getKeyName();
				} else {
					$datafield_selector = $dataField->getKeyName();
				}
				static::$columns[] = array(
					'datafield' => $datafield_selector,
					'text' => $dataField->retrieveValue('name'),
					'type' => $dataField->retrieveValue('type'),
					'storage_type' => $dataField->retrieveValue('storage_type')
				);
			}
		}
		return static::$columns;
	}

	/**
	 * Sets the last time the spy report ran
	 * @param unknown $value
	 */
	public function setLastEndTime($value) {
		$this->last_end_time = (int) $value;
	}

	/**
	 * Retrieves the interpreted start time (such as "yesterday" or "last week")
	 * @return mixed
	 */
	public function retrieveInterpretedStartTime() {
		$start_time_timestamp = 0;
		if ($this->retrieveValue('start_time_tz') instanceof \DateTime) {
			$start_time_timestamp = $this->retrieveValue('start_time_tz')->getTimestamp();
		}
		return max($start_time_timestamp, $this->retrieveValue('last_end_time'));
	}

	/**
	 * Retrieves the interpreted end time (such as "yesterday" or "last week")
	 * @return mixed
	 */
	public function retrieveInterpretedEndTime() {
		//we force the end_time to not exceed 5 seconds from the present server time
		$end_time = strtotime('-5 seconds');
		if($this->retrieveValue('end_time_tz') instanceof \DateTime) {
			$end_time = min($end_time, $this->retrieveValue('end_time_tz')->getTimestamp());
		}
		return $end_time;
	}

	/**
	 * Runs the report and queries the lead records (non-PHPdoc)
	 * @return array
	 */
	public function runReport() {
		//@todo: make the dates optional
		$this->massageDates();
		$match = array();
		$match = array(
			\Flux\DataField::DATA_FIELD_EVENT_CONTAINER => array('$elemMatch' => array('t' => array(
				'$gte' => new \MongoDate($this->retrieveInterpretedStartTime()),
				'$lt' => new \MongoDate($this->retrieveInterpretedEndTime())
			))),
		);

		if (count($this->getOfferId()) > 0) {
			$match[\Flux\DataField::DATA_FIELD_TRACKING_CONTAINER . '.' . \Flux\DataField::DATA_FIELD_REF_OFFER_ID . '._id'] = array('$in' => $this->getOfferId());
		}

		$lead = new \Flux\Lead();
		$lead->setSort(\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t');
		$lead->setSord("DESC");
		$lead->setItemsPerPage($this->getItemsPerPage());
		$ret_val = $lead->queryAll($match);
		foreach ($ret_val as $key => $value) {
			$ret_val[$key] = $value->toArray();
		}
		$this->setTotal($lead->getTotal());
		return $ret_val;
	}
}