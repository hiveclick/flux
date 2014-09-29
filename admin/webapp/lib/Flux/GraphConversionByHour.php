<?php
namespace Flux;

use Mojavi\Form\GoogleChart;
use Mojavi\Logging\Logger;
use Mojavi\Logging\LoggerManager;

class GraphConversionByHour extends GoogleChart {
	
    private $offer_id_array;
    
	/**
	 * Runs the report and stores the data
	 * @return boolean
	 */
	function compileReport() {
		$results = $this->queryLeads();				
		$this->addColumn('', 'Date', 'string');
		
		// Add columns for each offer
		$offer_names = array();
		foreach ($results['result'] as $item) {
			if (!in_array($item['offer_name'], $offer_names)) {
				$offer_names[] = $item['offer_name'];
			}
		}
		foreach ($offer_names as $offer_name) {
		    if (trim($offer_name) != '') {
			     $this->addColumn('', $offer_name, 'number');
		    }
		}
		
		// Add default rows for each hour and each offer
		$start_date = new \DateTime();
		$start_date->setTimestamp($this->getStartDate());
		$end_date = new \DateTime();
		$end_date->setTimestamp($this->getEndDate());
		$date_interval = $end_date->diff($start_date);
		$hours = ($date_interval->format("%d") * 24) + $date_interval->format("%h");
						
		for ($i=0;$i<=$hours;$i++) {
			$tmp_start_date = new \DateTime();
			$tmp_start_date->setTimestamp($this->getStartDate());
			$date_interval = new \DateInterval('PT' . $i . 'H');
			$tmp_start_date->add($date_interval);
			
			$col_counter = 0;
			foreach ($this->getCols() as $key => $column) {
				if ($col_counter == 0) {
					$row_data[0] = array('v' => $tmp_start_date->format('m/d/Y H:00:00'), 'f' => $tmp_start_date->format('H'));
				} else {
					$row_data[$col_counter] = array('v' => 0, 'f' => "0");
				}
				$col_counter++;
			}
			$this->addRow($tmp_start_date->format('m/d/Y H:00:00'), $row_data);
		}
			
		// Add the real data
		foreach ($results['result'] as $item) {
			if (trim($item['event_date']) != '') {
				$result_date = new \DateTime($item['event_date'] . ":00:00", new \DateTimeZone('UTC'));
				$result_date->setTimezone($this->getTimezone());
				$this->addData($result_date->format('m/d/Y H:00:00'), (trim($item['offer_name']) != '' ? $item['offer_name'] : 'Unknown'), intval($item['clicks']), intval($item['clicks']));
			}
		}
	}
	
	/**
	 * Queries the leads that have occurred in the past 24 hours
	 * @return array
	 */
	function queryLeads() {
		$lead = new \Flux\Lead();
		$ops = array();
		
		$ops[] = array(
			'$match' => array(
				\Flux\DataField::DATA_FIELD_EVENT_CONTAINER => array('$elemMatch' => 
					array('t' => 
						array(
							'$gte' => new \MongoDate($this->getStartDate()),
							'$lt' => new \MongoDate($this->getEndDate())
						)
					)
				)
			)
		);
		
		$ops[] = array(
				'$match' => array(
						\Flux\DataField::DATA_FIELD_EVENT_CONTAINER => array('$elemMatch' =>
								array('n' => \Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME)
						)
				)
		);
		
		$ops[] = array(
				'$match' => array(
						\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.t' => array('$exists' => 1)
				)
		);
		
		if (count($this->getOfferIdArray()) > 0) {
			$ops[] = array(
					'$match' => array(
							\Flux\DataField::DATA_FIELD_EVENT_CONTAINER . '.o' => array('$in' => $this->getOfferIdArray())
					)
			);
		}
		
		$ops[] = array(
				'$unwind' => '$' . \Flux\DataField::DATA_FIELD_EVENT_CONTAINER 
		);
		
		
		$ops[] = array('$project' => array(
			'_id' => '$_id',
			'event_date' => array('$substr' => 
								array('$_e.t', 0, 13)
							),
			'event_offer' => '$_e.o',
			'event_name' => '$_e.n',
			'offer_name' => '$_t._o.name',
			'clicks' => 1
		));
		
		
		$ops[] = array('$group' => array( 
				'_id' => array(
						'offer' => '$offer_name',
						'date' => '$event_date'
				),
				'offer_name' => array('$max' => '$offer_name'),
				'event_name' => array('$max' => '$event_name'),
				'event_date' => array('$max' => '$event_date'),
				'clicks' => array('$sum' => 1)
			)
		);
		
		return $lead->getCollection()->aggregate($ops);
	}	
	
	/**
	 * Returns the offer_id_array
	 * @return array
	 */
	function getOfferIdArray() {
		if (is_null($this->offer_id_array)) {
			$this->offer_id_array = array();
		}
		return $this->offer_id_array;
	}
	
	/**
	 * Sets the offer_id_array
	 * @var array
	 */
	function setOfferIdArray($arg0) {
		if (is_array($arg0)) {
			$this->offer_id_array = $arg0;
		} else if (is_string($arg0)) {
			if (strpos(",", $arg0) !== false) {
				$this->offer_id_array = explode(",", $arg0);
			} else {
				$this->offer_id_array = array($arg0);
			}
		}
		array_walk($this->offer_id_array, function(&$value, $key) { $value = intval($value); });
		$this->addModifiedColumn("offer_id_array");
		return $this;
	}
}