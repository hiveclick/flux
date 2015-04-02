<?php
namespace Flux;

class ReportLead extends Base\ReportLead {
    
    private $start_date;
    private $end_date;
    
    protected $day_of_year;
    
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
     * Queries entries by filters
     */
    function queryAll(array $criteria = array(), $hydrate = false) {
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