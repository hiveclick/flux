<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class ReportLead extends MongoForm {
    
    const LEAD_DISPOSITION_PENDING = 1;
    const LEAD_DISPOSITION_ACCEPTED = 2;
    const LEAD_DISPOSITION_DISQUALIFIED = 3;
    const LEAD_DISPOSITION_DUPLICATE = 4;
    
    protected $lead;
    protected $report_date;
    protected $client;
    protected $disposition;
    protected $disposition_message;
    protected $accepted;
    protected $payout;
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
            if ($this->client->getClientId() > 0 && $this->client->getClientName() == '') {
                $this->client->setClientName($this->client->getClient()->getName());
            }
            $this->addModifiedColumn("client");
        } else if (is_string($arg0) || is_int($arg0)) {
            $this->client = new \Flux\Link\Client();
            $this->client->setClientId($arg0);
            if ($this->client->getClientId() > 0 && $this->client->getClientName() == '') {
                $this->client->setClientName($this->client->getClient()->getName());
            }
            $this->addModifiedColumn("client");
        }
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
     * @return integer
     */
    function getDisposition() {
        if (is_null($this->disposition)) {
            $this->disposition = self::LEAD_DISPOSITION_PENDING;
        }
        return $this->disposition;
    }
    
    /**
     * Sets the disposition
     * @var integer
     */
    function setDisposition($arg0) {
        $this->disposition = (int)$arg0;
        $this->addModifiedColumn("disposition");
        return $this;
    }
    
    /**
     * Returns the disposition_message
     * @return string
     */
    function getDispositionMessage() {
        if (is_null($this->disposition_message)) {
            $this->disposition_message = "";
        }
        return $this->disposition_message;
    }
    
    /**
     * Sets the disposition_message
     * @var string
     */
    function setDispositionMessage($arg0) {
        $this->disposition_message = $arg0;
        $this->addModifiedColumn("disposition_message");
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
        $this->payout = floatval($arg0);
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
        } else if ($arg0 instanceof \MongoId) {
            $this->lead = new \Flux\Link\Lead();
            $this->lead->setLeadId((string)$arg0);
            if (\MongoId::isValid($this->lead->getLeadId()) && $this->lead->getLeadName() == '') {
                $this->lead->setLeadName($this->lead->getLead()->getId());
            }
            $this->addModifiedColumn("lead");
        }
        return $this;
    }
    
    // +------------------------------------------------------------------------+
    // | HELPER METHODS															|
    // +------------------------------------------------------------------------+
    
    /**
     * Ensures that the mongo indexes are set (should be called once)
     * @return boolean
     */
    public static function ensureIndexes() {
        $report_lead = new self();
        $report_lead->getCollection()->ensureIndex(array('report_date' => 1, 'lead.lead_id' => 1), array('unique' => true, 'background' => true));
        return true;
    }
}