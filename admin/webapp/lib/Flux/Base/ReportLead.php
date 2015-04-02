<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class ReportLead extends MongoForm {
    
    protected $lead;
    protected $report_date;
    protected $disposition;
    protected $accepted;
    protected $revenue;
    
    /**
     * Constructs new user
     * @return void
     */
    function __construct() {
        $this->setCollectionName('report_lead');
        $this->setDbName('admin');
        $this->setIdType(self::ID_TYPE_MONGO);
    }
    
    /**
     * Returns the report_date
     * @return \MongoDate
     */
    function getReportDate() {
        if (is_null($this->report_date)) {
            $this->report_date = new \MongoDate();
        }
        return $this->report_date;
    }
    
    /**
     * Sets the report_date
     * @var \MongoDate
     */
    function setReportDate($arg0) {
        if ($arg0 instanceof \MongoDate) {
            $this->report_date = $arg0;
        } else if (is_string($arg0)) {
            $this->report_date = new \MongoDate(strtotime($arg0));
        } else if (is_int($arg0)) {
            $this->report_date = new \MongoDate($arg0);
        }
        $this->addModifiedColumn('report_date');
        return $this;
    }
    
    /**
     * Returns the accepted
     * @return boolean
     */
    function getAccepted() {
        if (is_null($this->accepted)) {
            $this->accepted = false;
        }
        return $this->accepted;
    }
    
    /**
     * Sets the accepted
     * @var boolean
     */
    function setAccepted($arg0) {
        $this->accepted = (boolean)$arg0;
        $this->addModifiedColumn("accepted");
        return $this;
    }
    
    /**
     * Returns the disposition
     * @return string
     */
    function getDisposition() {
        if (is_null($this->disposition)) {
            $this->disposition = "";
        }
        return $this->disposition;
    }
    
    /**
     * Sets the disposition
     * @var string
     */
    function setDisposition($arg0) {
        $this->disposition = $arg0;
        $this->addModifiedColumn("disposition");
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
        $this->revenue = floatval($arg0);
        $this->addModifiedColumn("revenue");
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
            $this->lead = new \Flux\Link\Lead();
            $this->lead->populate($arg0);
            if (\MongoId::isValid($this->lead->getLeadId()) && $this->lead->getLeadName() == '') {
                $this->lead->setLeadName($this->lead->getLead()->getId());
            }
            $this->addModifiedColumn("lead");
        } else if (is_string($arg0) && \MongoId::isValid($arg0)) {
            $this->lead = new \Flux\Link\Lead();
            $this->lead->setLeadId($arg0);
            if (\MongoId::isValid($this->lead->getLeadId()) && $this->lead->getLeadName() == '') {
                $this->lead->setLeadName($this->lead->getLead()->getId());
            }
            $this->addModifiedColumn("lead");
        }
        return $this;
    }
}