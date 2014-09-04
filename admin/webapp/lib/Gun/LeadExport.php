<?php
namespace Gun;

use Mojavi\Form\MojaviForm;

class LeadExport extends MojaviForm {

    protected $client_export_id;
    protected $client_export_name;
    protected $revenue;
    protected $export_date;
    
    private $client_export;
    
    /**
     * Returns the client_export_id
     * @return integer
     */
    function getClientExportId() {
    	if (is_null($this->client_export_id)) {
    		$this->client_export_id = 0;
    	}
    	return $this->client_export_id;
    }
    
    /**
     * Sets the client_export_id
     * @var integer
     */
    function setClientExportId($arg0) {
    	$this->client_export_id = (int)$arg0;
    	$this->addModifiedColumn("client_export_id");
    	$this->addModifiedColumn("client_export_name");
    	return $this;
    }
    
    /**
     * Returns the client_export_name
     * @return string
     */
    function getClientExportName() {
    	if (is_null($this->client_export_name)) {
    		$this->client_export_name = $this->getClientExport()->getName();
    	}
    	return $this->client_export_name;
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
     * Returns the export_date
     * @return MongoDate
     */
    function getExportDate() {
    	if (is_null($this->export_date)) {
    		$this->export_date = new \MongoDate();
    	}
    	return $this->export_date;
    }
    
    /**
     * Sets the export_date
     * @var MongoDate
     */
    function setExportDate($arg0) {
        if (is_integer($arg0)) {
            $this->export_date = new \MongoDate($arg0);
        } else if ($arg0 instanceof \MongoDate) {
            $this->export_date = $arg0;
        }
    	$this->addModifiedColumn("export_date");
    	return $this;
    }    
    
    /**
     * Returns the client_export
     * @return \Gun\ClientExport
     */
    function getClientExport() {
    	if (is_null($this->client_export)) {
    		$this->client_export = new \Gun\ClientExport();
    		$this->client_export->setId($this->getClientExportId());
    		$this->client_export->query();
    	}
    	return $this->client_export_id;
    }
}