<?php
namespace Flux;

class ReportLead extends Base\ReportLead {
    
    private $start_date;
    private $end_date;
    
    private $client_id_array;
    private $disposition_array;
    
    protected $day_of_year;
    
    /**
     * Sets the report_date
     * @var \MongoDate
     */
    function setReportDate($arg0) {
        parent::setReportDate($arg0);
        $this->setStartDate(date('m/01/Y', $this->getReportDate()->sec));
        $this->setEndDate(date('m/t/Y', $this->getReportDate()->sec));
        return $this;
    }
    
    /**
     * Returns the start_date
     * @return string
     */
    function getStartDate() {
        if (is_null($this->start_date)) {
            $this->start_date = "";
        }
        return $this->start_date;
    }
    
    /**
     * Sets the start_date
     * @var string
     */
    function setStartDate($arg0) {
        $this->start_date = $arg0;
        $this->addModifiedColumn("start_date");
        return $this;
    }
    
    /**
     * Returns the end_date
     * @return string
     */
    function getEndDate() {
        if (is_null($this->end_date)) {
            $this->end_date = "";
        }
        return $this->end_date;
    }
    
    /**
     * Sets the end_date
     * @var string
     */
    function setEndDate($arg0) {
        $this->end_date = $arg0;
        $this->addModifiedColumn("end_date");
        return $this;
    }
    
    /**
     * Returns the day_of_year
     * @return string
     */
    function getDayOfYear() {
        return date('z', $this->getReportDate()->sec);
    }
    
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
            array_walk($this->client_id_array, function(&$value) { $value = (int)$value; });
        } else if (is_string($arg0) || is_int($arg0)) {
            if (strpos($arg0, ',') !== false) {
                $this->client_id_array = explode(",", $arg0);
            } else {
                $this->client_id_array = array($arg0);
            }
            array_walk($this->client_id_array, function(&$value) { $value = (int)$value; });
        }
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
            array_walk($this->disposition_array, function(&$value) { $value = (int)$value; });
        } else if (is_string($arg0) || is_int($arg0)) {
            if (strpos($arg0, ',') !== false) {
                $this->disposition_array = explode(",", $arg0);
            } else {
                $this->disposition_array = array($arg0);
            }
            array_walk($this->disposition_array, function(&$value) { $value = (int)$value; });
        }
        $this->addModifiedColumn("disposition_array");
        return $this;
    }
   
    /**
     * Queries entries by filters
     */
    function queryAll(array $criteria = array(), $hydrate = false) {
        if (count($this->getDispositionArray()) > 0) {
            $criteria['disposition'] = array('$in' => $this->getDispositionArray());
        }
        if (count($this->getClientIdArray()) > 0) {
            $criteria['client.client_id'] = array('$in' => $this->getClientIdArray());
        }
        if ($this->getStartDate() != '' && $this->getEndDate() != '') {
            $criteria['report_date'] = array('$gte' => new \MongoDate(strtotime($this->getStartDate())), '$lte' => new \MongoDate(strtotime($this->getEndDate())));
        }
        return parent::queryAll($criteria, $hydrate);
    }
    
    /**
     * Ensures that the mongo indexes are set (should be called once)
     * @return boolean
     */
    public static function ensureIndexes() {
        $report_lead = new self();
        $report_lead->getCollection()->ensureIndex(array('lead.lead_id' => 1, 'report_date' => 1), array('unique' => true, 'background' => true));
        return true;
    }
    
}