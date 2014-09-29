<?php
namespace Flux;

use Mojavi\Logging\LoggerManager;
class RevenueReport extends BaseReport {

	protected static $columns;
	protected static $groups;

	public function __construct() {
		$this->setCollectionName('lead');
		$this->setDbName('lead');
	}

	/**
	 * Returns the data event id
	 * @return integer
	 */
	public function getDateEventId() {
		if(is_null($this->date_event_id)) {
			$this->date_event_id = (string) \Flux\DataField::retrieveDataFieldFromName(\Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME)->getId();
		}
		return $this->date_event_id;
	}

	/**
	 * Returns the columns to use
	 * @return array
	 */
	public static function getColumns() {
		if (is_null(static::$columns)) {
			static::$columns = array();
			/* @var $report_column \Flux\ReportColumn */
			$report_column_obj = new \Flux\ReportColumn();
			$report_column_obj->setSort('name');
			$report_column_obj->setSord('ASC');
			$report_column_obj->setIgnorePagination(true);
			$report_columns = $report_column_obj->queryAll();
			foreach ($report_columns as $report_column) {
				static::$columns[] = array(
						'column_id' => $report_column->getId(),
						'text' => $report_column->getName(),
						'type' => $report_column->getColumnType(),
						'format_type' => $report_column->getFormatType()
				);
			}
		}
		return static::$columns;
	}

	/**
	 * Returns the groups to use
	 * @return array
	 */
	public static function getGroups() {
		if(is_null(static::$groups)) {
			static::$groups = array();

			static::$groups[] = array(
					'group_id' => 'geo',
					'group_selector' => \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.o',
					'text' => 'Event Offer',
					'type' => \Flux\DataField::DATA_FIELD_TYPE_OFFER_ID,
					'storage_type' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT
			);

			static::$groups[] = array(
					'group_id' => 'gec',
					'group_selector' => \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.c',
					'text' => 'Event Campaign',
					'type' => \Flux\DataField::DATA_FIELD_TYPE_CLIENT_ID,
					'storage_type' => \Flux\DataField::DATA_FIELD_STORAGE_TYPE_EVENT
			);

			foreach(\Flux\DataField::retrieveActiveGroupingDataFields() AS $dataField) {
				$group_selector = '';
				if ($dataField->getStorageType() === dataField::DATA_FIELD_STORAGE_TYPE_EVENT) {
					$group_selector = \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.' . $dataField->retrieveValue('_id');
				} else {
					//assume dataField::DATA_FIELD_DEFAULT_CONTAINER
					$group_selector = \Flux\DataField::DATA_FIELD_DEFAULT_CONTAINER . '.' . \Flux\Lead::LEAD_DATA_FIELD_PREFIX . $dataField->retrieveValue('_id');
				}

				static::$groups[] = array(
						'group_id' => 'g' . $dataField->getId(),
						'group_selector' => $group_selector,
						'text' => $dataField->getName(),
						'type' => $dataField->getType(),
						'storage_type' => $dataField->getStorageType()
				);
			}
		}
		return static::$groups;
	}

	/**
	 * Returns the group from it's id
	 * @param integer $group_id
	 * @return array|NULL
	 */
	public static function retrieveGroupFromId($group_id) {
		foreach(self::getGroups() AS $group_object) {
			if ($group_object['group_id'] == $group_id) {
				return $group_object;
			}
		}
		return null;
	}

	/**
	 * Runs the report and queries the lead records (non-PHPdoc)
	 * @return array
	 */
	public function runReport() {
		//@todo: make the dates optional
		$this->massageDates();
		$query = array();
		$return = null;

		$match = array(
				\Flux\DataField::DATA_FIELD_EVENT_CONTAINER => array('$elemMatch' => array('t' => array('$gte' => new \MongoDate($this->retrieveValue('start_time_tz')->getTimestamp())))),
		);
		$explicit_match = array(
				\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t' => array('$gte' => new \MongoDate($this->retrieveValue('start_time_tz')->getTimestamp()))
		);
		//if(isset($to)) {
		$match[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER]['$elemMatch']['t']['$lt'] = new \MongoDate($this->retrieveValue('end_time_tz')->getTimestamp());
		$explicit_match[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t']['$lt'] = new \MongoDate($this->retrieveValue('end_time_tz')->getTimestamp());
		//}
		if(count($this->retrieveValue('offer_id')) > 0) {
			$match[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER]['$elemMatch']['o'] = array('$in' => $this->retrieveValue('offer_id'));
			$explicit_match[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.o'] = array('$in' => $this->retrieveValue('offer_id'));
		}
		if(count($this->retrieveValue('campaign_id')) > 0) {
			$match[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER]['$elemMatch']['c'] = array('$in' => $this->retrieveValue('campaign_id'));
			$explicit_match[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.c'] = array('$in' => $this->retrieveValue('campaign_id'));
		}
		if(count($this->retrieveValue('event_id')) > 0) {
			$match[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER]['$elemMatch']['n'] = array('$in' => $this->retrieveValue('event_id'));
			if($this->retrieveValue('event_window') === self::EVENT_WINDOW_EXCLUSIVE_EXCPLICIT) {
				$explicit_match[\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.n'] = array('$in' => $this->retrieveValue('event_id'));
			}
		}

		$query[] = array('$match' => $match);
		$query[] = array('$unwind' => '$' . \Flux\DataField::DATA_FIELD_EVENT_CONTAINER);

		if(in_array($this->retrieveValue('event_window'), array(self::EVENT_WINDOW_EXCLUSIVE_ANY, self::EVENT_WINDOW_EXCLUSIVE_EXCPLICIT))) {
			$query[] = array('$match' => $explicit_match);
		}

		$group_initial_column_array = array();
		$calculation_initial_column_array = array();
		foreach($this->retrieveValue('column_id') AS $column_id) {
			$report_column = new \Flux\ReportColumn();
			$report_column->setId($column_id);
			$report_column->query();

			if (!is_null($report_column)) {
				$column_name = 'c' . $column_id;
				if($report_column->retrieveValue('type') === \Flux\ReportColumn::REPORT_COLUMN_TYPE_GROUP_DATAFIELD) {
					$event_id_array = $report_column->retrieveValue('parameter');
					if($report_column->retrieveValue('sum_type') === \Flux\ReportColumn::COLUMN_SUM_VALUE) {
						$event_array = array();
						foreach($event_id_array AS $event_id) {
							$event_array[] = array('$eq' => array('$' . \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.n', $event_id));
						}
						$group_initial_column_array[$column_name] = array('$max' => array('$cond' => array(array('$or' => $event_array), '$' . \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.v', 0)));
					} else if($report_column->retrieveValue('sum_type') === \Flux\ReportColumn::COLUMN_COUNT_TOTAL) {
						$event_array = array();
						foreach($event_id_array AS $event_id) {
							$event_array[] = array('$eq' => array('$' . \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.n', $event_id));
						}
						$group_initial_column_array[$column_name] = array('$max' => array('$cond' => array(array('$or' => $event_array), 1, 0)));
						//$group_array['$group'][$column_name] = array('$sum' => array('$cond' => array(array('$setEquals' => array(array('$' . \Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.n'), $event_id_array)), 1, 0)));
					}
				} else if($report_column->retrieveValue('type') === \Flux\ReportColumn::REPORT_COLUMN_TYPE_CALCULATION) {
					$report_column_id_array = $report_column->retrieveValue('parameter');
					$report_column_name_array = array();
					foreach($report_column_id_array AS $report_column_id) {
						$report_column_name_array[] = '$c' . $report_column_id;
					}
					$mongo_operator = \Flux\ReportColumn::convertOperatorTypeToMongo($report_column->retrieveValue('operator_type'));
					if($mongo_operator === '$divide') {
						if(count($report_column_name_array) == 2) {
							$divisor = $report_column_name_array[1];
							$calculation_initial_column_array[$column_name] = array('$cond' => array(array('$eq' => [$divisor, 0]), 0, array($mongo_operator => $report_column_name_array)));
						}
					} else {
						$calculation_initial_column_array[$column_name] = array($mongo_operator => $report_column_name_array);
					}
				}
			}
		}
		if (count($this->retrieveValue('group_id')) > 0) {
			foreach($this->retrieveValue('group_id') AS $group_id) {
				$group_object = self::retrieveGroupFromId($group_id);
				if(is_array($group_object)) {
					$groupId = '$' . $group_object['group_selector'];
				}
				$group_initial_column_array[$group_id] = array('$max' => $groupId);
			}
		}
		$group_initial_column_id_array = array('_id' => '$_id') + $group_initial_column_array;
		$query[] = array('$group' => $group_initial_column_id_array);

		$group_final_column_array = array();
		foreach($group_initial_column_array AS $column_name => $group_data) {
			$group_final_column_array[$column_name] = array('$sum' => '$' . $column_name);
		}

		$group_final_column_id_array = array();
		if(count($this->retrieveValue('group_id')) > 0) {
			foreach($this->retrieveValue('group_id') AS $group_id) {
				$group_final_column_id_array['_id'][$group_id] = '$' . $group_id;
			}
		} else {
			$group_final_column_id_array['_id'] = null;
		}
		$group_final_column_id_array = $group_final_column_id_array + $group_final_column_array;
		$query[] = array('$group' => $group_final_column_id_array);

		$project_column_id_array = array();
		foreach($group_final_column_id_array AS $key_id => $group_info) {
			$project_column_id_array[$key_id] = '$' . $key_id;
		}

		$project_column_id_calc_array = $project_column_id_array + $calculation_initial_column_array;
		$query[] = array('$project' => $project_column_id_calc_array);

		$query[] = array('$sort' => array('_id' => 1));

		$agg_result = $this->getCollection()->aggregate($query);
		$return = $agg_result['result'];
		return $return;
	}
}