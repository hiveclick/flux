<?php
namespace Flux\Report;

class LeadPayout extends BaseReport {
    
    const GROUPING_NONE = 0;
    const GROUPING_DATE = 1;
    const GROUPING_CLIENT = 2;
    
    private $client_id_array;
    private $disposition_array;
    private $grouping;
    
    protected $client;
    protected $accepted_leads;
    protected $disqualified_leads;
    protected $pending_leads;
    protected $payout;
    protected $revenue;
    
    /**
     * Returns the client_id_array
     * @return array
     */
    function getClientIdArray() {
        if (is_null($this->client_id_array)) {
            $this->client_id_array = array();
        }
        return $this->client_id_array;
    }
    
    /**
     * Sets the client_id_array
     * @var array
     */
    function setClientIdArray($arg0) {
        if (is_array($arg0)) {
            $this->client_id_array = $arg0;
        } else if (is_string($arg0)) {
            if (strpos($arg0, ",") !== false) {
                $this->client_id_array = explode(",", $arg0);
            } else {
                $this->client_id_array = array($arg0);
            }
        }
        array_walk($this->client_id_array, function(&$value) { $value = (int)$value; });
        $this->addModifiedColumn("client_id_array");
        return $this;
    }
    
    /**
     * Returns the disposition_array
     * @return array
     */
    function getDispositionArray() {
        if (is_null($this->disposition_array)) {
            $this->disposition_array = array();
        }
        return $this->disposition_array;
    }
    
    /**
     * Sets the disposition_array
     * @var array
     */
    function setDispositionArray($arg0) {
        if (is_array($arg0)) {
            $this->disposition_array = $arg0;
        } else if (is_string($arg0)) {
            if (strpos($arg0, ",") !== false) {
                $this->disposition_array = explode(",", $arg0);
            } else {
                $this->disposition_array = array($arg0);
            }
        }
        array_walk($this->disposition_array, function(&$value) { $value = (int)$value; });
        $this->addModifiedColumn("disposition_array");
        return $this;
    }
    
    /**
     * Returns the grouping
     * @return integer
     */
    function getGrouping() {
        if (is_null($this->grouping)) {
            $this->grouping = self::GROUPING_NONE;
        }
        return $this->grouping;
    }
    
    /**
     * Sets the grouping
     * @var integer
     */
    function setGrouping($arg0) {
        $this->grouping = $arg0;
        $this->addModifiedColumn("grouping");
        return $this;
    }
    
    
    
    /**
     * Returns the client
     * @return \Flux\Link\Client
     */
    function getClient() {
        if (is_null($this->client)) {
            $this->client = new \Flux\Link\Client();
        }
        return $this->client;
    }
    
    /**
     * Sets the client
     * @var \Flux\Link\Client
     */
    function setClient($arg0) {
        if (is_array($arg0)) {
            $this->client = new \Flux\Link\Client();
            $this->client->populate($arg0);
            if ($this->client->getClientId() > 0 && trim($this->client->getClientName() == "")) {
                $this->client->setClientName($this->client->getClient()->getName());
            }
        } else if (is_string($arg0) || is_int($arg0)) {
            $this->client = new \Flux\Link\Client();
            $this->client->setClientId($arg0);
            if ($this->client->getClientId() > 0 && trim($this->client->getClientName() == "")) {
                $this->client->setClientName($this->client->getClient()->getName());
            }
        }
        $this->addModifiedColumn("client");
        return $this;
    }
    
    /**
     * Returns the accepted_leads
     * @return integer
     */
    function getAcceptedLeads() {
        if (is_null($this->accepted_leads)) {
            $this->accepted_leads = 0;
        }
        return $this->accepted_leads;
    }
    
    /**
     * Sets the accepted_leads
     * @var integer
     */
    function setAcceptedLeads($arg0) {
        $this->accepted_leads = (int)$arg0;
        $this->addModifiedColumn("accepted_leads");
        return $this;
    }
    
    /**
     * Returns the disqualified_leads
     * @return integer
     */
    function getDisqualifiedLeads() {
        if (is_null($this->disqualified_leads)) {
            $this->disqualified_leads = 0;
        }
        return $this->disqualified_leads;
    }
    
    /**
     * Sets the disqualified_leads
     * @var integer
     */
    function setDisqualifiedLeads($arg0) {
        $this->disqualified_leads = (int)$arg0;
        $this->addModifiedColumn("disqualified_leads");
        return $this;
    }
    
    /**
     * Returns the pending_leads
     * @return integer
     */
    function getPendingLeads() {
        if (is_null($this->pending_leads)) {
            $this->pending_leads = 0;
        }
        return $this->pending_leads;
    }
    
    /**
     * Sets the pending_leads
     * @var integer
     */
    function setPendingLeads($arg0) {
        $this->pending_leads = (int)$arg0;
        $this->addModifiedColumn("pending_leads");
        return $this;
    }
    
    /**
     * Returns the payout
     * @return float
     */
    function getPayout() {
        if (is_null($this->payout)) {
            $this->payout = 0.00;
        }
        return $this->payout;
    }
    
    /**
     * Sets the payout
     * @var float
     */
    function setPayout($arg0) {
        $this->payout = (float)$arg0;
        $this->addModifiedColumn("payout");
        return $this;
    }
    
    /**
     * Returns the revenue
     * @return float
     */
    function getRevenue() {
        if (is_null($this->revenue)) {
            $this->revenue = 0.00;
        }
        return $this->revenue;
    }
    
    /**
     * Sets the revenue
     * @var float
     */
    function setRevenue($arg0) {
        $this->revenue = (float)$arg0;
        $this->addModifiedColumn("revenue");
        return $this;
    }
    
    /**
     * Runs the report and aggregates the results
     * @return boolean
     */
    function compileReport() {
        $ret_val = array();
        $ops = array();
		
        $criteria = array();
        if (count($this->getClientIdArray()) > 0) {
            $criteria['client.client_id'] = array('$in' => $this->getClientIdArray());
        }
        if (count($this->getDispositionArray()) > 0) {
            $criteria['disposition'] = array('$in' => $this->getDispositionArray());
        }
        if (trim($this->getKeywords()) != '') {
            $criteria['$or'] = array(
                array('lead.lead_name' => new \MongoRegex('/' . $this->getKeywords() . '/i')),
                array('lead.email' => new \MongoRegex('/' . $this->getKeywords() . '/i')),
                array('lead.lead_id' => new \MongoRegex('/' . $this->getKeywords() . '/i')),
            );
        }
        $this->massageDates();
        $criteria['report_date'] = array('$gte' => new \MongoDate(strtotime($this->getStartTime())), '$lte' => new \MongoDate(strtotime($this->getEndTime())));
                
		$ops[] = array(
			'$match' => $criteria
		);
		$ops[] = array(
		    '$project' => array(
                'client_name' => '$client.client_name',
		        'client_id' => '$client.client_id',
		        'report_date' => '$report_date',
		        'payout' => array('$cond' => array(array('$eq' => array('$accepted', true)), '$payout', 0)),
		        'revenue' => array('$cond' => array(array('$eq' => array('$accepted', true)), '$revenue', 0)),
		        'accepted_leads' => array('$cond' => array(array('$eq' => array('$disposition', \Flux\ReportLead::LEAD_DISPOSITION_ACCEPTED)), 1, 0)),
		        'disqualified_leads' => array('$cond' => array(array('$eq' => array('$disposition', \Flux\ReportLead::LEAD_DISPOSITION_DISQUALIFIED)), 1, 0)),
		        'duplicate_leads' => array('$cond' => array(array('$eq' => array('$disposition', \Flux\ReportLead::LEAD_DISPOSITION_DUPLICATE)), 1, 0)),
		        'pending_leads' => array('$cond' => array(array('$eq' => array('$disposition', \Flux\ReportLead::LEAD_DISPOSITION_PENDING)), 1, 0)),
		    )
		);
		$ops[] = array(
		    '$group' => array(
    		    '_id' => array(
    		        'client_id' => '$client_id'
    		    ),
    		    'client_name' => array('$max' => '$client_name'),
    		    'client_id' => array('$max' => '$client_id'),
    		    'report_date' => array('$max' => '$report_date'),
    		    'accepted_leads' => array('$sum' => '$accepted_leads'),
    		    'disqualified_leads' => array('$sum' => '$disqualified_leads'),
		        'duplicate_leads' => array('$sum' => '$duplicate_leads'),
		        'pending_leads' => array('$sum' => '$pending_leads'),
    		    'payout' => array('$sum' => '$payout'),
		        'revenue' => array('$sum' => '$revenue'),
    		)
		);
		
		$op_query = json_encode($ops);
		$op_query = str_replace('"$group"', '$group', $op_query);
		$op_query = str_replace('"$max"', '$max', $op_query);
		$op_query = str_replace('"$sum"', '$sum', $op_query);
		$op_query = str_replace('"$unwind"', '$unwind', $op_query);
		$op_query = str_replace('"$match"', '$match', $op_query);
		$op_query = str_replace('"$gte"', '$gte', $op_query);
		$op_query = str_replace('"$lt"', '$lt', $op_query);
		$op_query = str_replace('"$substr"', '$substr', $op_query);
		$op_query = str_replace('"$exists"', '$exists', $op_query);
		$op_query = str_replace('"$elemMatch"', '$elemMatch', $op_query);
		$op_query = str_replace('"$project"', '$project', $op_query);
		$start_date = new \MongoDate(strtotime($this->getStartTime()));
		$end_date = new \MongoDate(strtotime($this->getEndTime()));
		$op_query = str_replace(json_encode($start_date), 'ISODate(\'' . $start_date->toDateTime()->format(\DateTime::ISO8601) . '\')', $op_query);
		$op_query = str_replace(json_encode($end_date), 'ISODate(\'' . $end_date->toDateTime()->format(\DateTime::ISO8601) . '\')', $op_query);
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $op_query);
		
		/* @var $report_lead \Flux\ReportLead */
		$report_lead = new \Flux\ReportLead();
		$results = $report_lead->getCollection()->aggregate($ops);
		if (isset($results['result'])) {
    		foreach ($results['result'] as $result) {
    		    $result['clicks'] = 0;
    		    $result['conversions'] = 0;
    		    $ret_val[] = $result;
    		}
		}
		
		// Now query the clicks and conversions
		$ops = array();
		$criteria = array();
		if (count($this->getClientIdArray()) > 0) {
		    $criteria['_t.client.client_id'] = array('$in' => $this->getClientIdArray());
		}
		$this->massageDates();
	    $criteria['_id'] = array('$gte' => $this->createMongoIdFromTimestamp(strtotime($this->getStartTime())), '$lte' => $this->createMongoIdFromTimestamp(strtotime($this->getEndTime())));
	    
	    
	    if (trim($this->getKeywords()) != '') {
	        $criteria['$or'] = array(
	            array('_d.fn' => new \MongoRegex('/' . $this->getKeywords() . '/i')),
	            array('_d.ln' => new \MongoRegex('/' . $this->getKeywords() . '/i')),
	            array('_d.email' => new \MongoRegex('/' . $this->getKeywords() . '/i')),
	            array('_id' => new \MongoRegex('/' . $this->getKeywords() . '/i')),
	        );
	    }
		$ops[] = array(
		    '$match' => $criteria
		);
		$ops[] = array(
		    '$unwind' => '$_e'
		);
		$ops[] = array(
		    '$project' => array(
		        'client_name' => '$_t.client.client_name',
		        'client_id' => '$_t.client.client_id',
		        'report_date' => '$created_time',
		        'clicks' => array('$cond' => array(array('$eq' => array('$_e.data_field.data_field_key_name', \Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME)), 1, 0)),
		        'conversions' => array('$cond' => array(array('$eq' => array('$_e.data_field.data_field_key_name', \Flux\DataField::DATA_FIELD_EVENT_CONVERSION_NAME)), 1, 0)),
		        'fulfilled' => array('$cond' => array(array('$eq' => array('$_e.data_field.data_field_key_name', \Flux\DataField::DATA_FIELD_EVENT_FULFILLED_NAME)), 1, 0))
		    )
		);
		$ops[] = array(
		    '$group' => array(
		        '_id' => array(
		            'client_id' => '$client_id'
		        ),
		        'client_name' => array('$max' => '$client_name'),
		        'client_id' => array('$max' => '$client_id'),
		        'report_date' => array('$max' => '$report_date'),
		        'clicks' => array('$sum' => '$clicks'),
		        'conversions' => array('$sum' => '$conversions'),
		        'fulfilled' => array('$sum' => '$fulfilled')
		    )
		);
		$op_query = json_encode($ops);
		$op_query = str_replace('"$group"', '$group', $op_query);
		$op_query = str_replace('"$max"', '$max', $op_query);
		$op_query = str_replace('"$sum"', '$sum', $op_query);
		$op_query = str_replace('"$unwind"', '$unwind', $op_query);
		$op_query = str_replace('"$match"', '$match', $op_query);
		$op_query = str_replace('"$gte"', '$gte', $op_query);
		$op_query = str_replace('"$lt"', '$lt', $op_query);
		$op_query = str_replace('"$substr"', '$substr', $op_query);
		$op_query = str_replace('"$exists"', '$exists', $op_query);
		$op_query = str_replace('"$elemMatch"', '$elemMatch', $op_query);
		$op_query = str_replace('"$project"', '$project', $op_query);
		$start_date = new \MongoDate(strtotime($this->getStartTime()));
		$end_date = new \MongoDate(strtotime($this->getEndTime()));
		$op_query = str_replace(json_encode($start_date), 'ISODate(\'' . $start_date->toDateTime()->format(\DateTime::ISO8601) . '\')', $op_query);
		$op_query = str_replace(json_encode($end_date), 'ISODate(\'' . $end_date->toDateTime()->format(\DateTime::ISO8601) . '\')', $op_query);
		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $op_query);
		
		/* @var $lead \Flux\Lead */
		$lead = new \Flux\Lead();
		$results = $lead->getCollection()->aggregate($ops);
		if (isset($results['result'])) {
		    foreach ($results['result'] as $key => $result) {
		        \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($result, true));
		        foreach ($ret_val as $key => $ret_result) {
		            if ($ret_result['client_id'] == $result['client_id']) {
		                $ret_result['clicks'] = $result['clicks'];
		                $ret_result['conversions'] = $result['conversions'];
		                $ret_result['fulfilled'] = $result['fulfilled'];
		                $ret_val[$key] = $ret_result;
		            }
		        }
		    }
		}
		
		return $ret_val;
    }
    
    /**
     * Creates a mongoid from a timestamp
     * @return \MongoId
     */
    public function createMongoIdFromTimestamp($timestamp)
    {
        $inc = 0;
        $ts = pack('N', $timestamp);
        $m = substr(md5(gethostname()), 0, 3);
        $pid = pack('n', posix_getpid());
        $trail = substr(pack('N', $inc++), 1, 3);
    
        $bin = sprintf('%s%s%s%s', $ts, $m, $pid, $trail);
    
        $id = '';
        for ($i = 0; $i < 12; $i++ ) {
            $id .= sprintf('%02X', ord($bin[$i]));
        }
    
        return new \MongoID($id);
    }
}

