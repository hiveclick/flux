<?php
namespace Flux;

class Fulfillment extends Base\Fulfillment {

	private $client_id_array;
	private $lead_id;
	
	/**
	 * Returns the lead_id
	 * @return string
	 */
	function getLeadId() {
		if (is_null($this->lead_id)) {
			$this->lead_id = "";
		}
		return $this->lead_id;
	}
	
	/**
	 * Sets the lead_id
	 * @var string
	 */
	function setLeadId($arg0) {
		$this->lead_id = $arg0;
		return $this;
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
		$this->client_id_array = $arg0;
		array_walk($this->client_id_array, function(&$val) { $val = intval(trim($val)); });
		return $this;
	}
	
	/**
	 * Queries for a list of exports by client id
	 * @return \Flux\Export
	 */
	function queryAll(array $criteria = array(), $hydrate = true) {
		if (trim($this->getKeywords()) != '') {
			$criteria['$or'] = array(
				array('name' => new \MongoRegex("/" . $this->getKeywords() . "/i"))
			);
		}
		if (count($this->getClientIdArray()) > 0) {
			$criteria['client.client_id'] = $this->getClientIdArray();
		}
		return parent::queryAll($criteria, $hydrate);
	}

	/**
	 * Queries for a list of exports by client id
	 * @return \Flux\Export
	 */
	function queryAllByClient() {
		return parent::queryAll(array('client.client_id' => $this->getClient()->getClientId()));
	}

	/**
	 * Flushes the mapping
	 * @return Flux\Fulfillment
	 */
	function flushMapping() {
		$this->setMapping(array());
		parent::addModifiedColumn('mapping');
		return $this->update();
	}
	
	/**
	 * Flushes the mapping
	 * @return Flux\Fulfillment
	 */
	function flushSchedule() {
		$this->setScheduling(array());
		parent::addModifiedColumn('scheduling');
		return $this->update();
	}
	
	/**
	 * Tests the fulfillment mapping
	 * @return Flux\Fulfillment
	 */
	function testFulfillment() {
		/* @var $export_class \Flux\Export\ExportAbstract */
		$export_class = $this->getExportClass();
		
		$params = array();
		/* @var $mapping \Flux\FulfillmentMap */
		foreach ($this->getMapping() as $mapping) {
			$value = $mapping->getMappedValue($lead);
			if (trim($mapping->getFieldName()) == '') {
				$params[$mapping->getDataField()->getKeyName()] = $value;
			} else {
				$params[$mapping->getFieldName()] = $value;
			}
		}
		/* @todo finish this */
		
		return true;
	}

	/**
	 * Tests an ftp connection
	 * @return boolean
	 */
	function testFtp() {
		if (trim($this->getFtpHostname()) != '') {
			if ((@$ftp_conn = ftp_connect($this->getFtpHostname(), $this->getFtpPort())) !== false) {
				if (@ftp_login($ftp_conn, $this->getFtpUsername(), $this->getFtpPassword())) {
					if (trim($this->getFtpFolder()) !== '') {
						if (!@ftp_chdir($ftp_conn, $this->getFtpFolder())) {
							throw new \Exception('We can login to the ftp server at ' . $this->getFtpHostname() . ', but the folder /' . $this->getFtpFolder() . ' does not exist.');
						}
					}
				} else {
					throw new \Exception('We can connect to the ftp server at ' . $this->getFtpHostname() . ', but cannot login to it.');
				}
			} else {
				throw new \Exception('Cannot connect to the ftp server at ' . $this->getFtpHostname());
			}
		} else {
			throw new \Exception('You did not enter a valid ftp hostname');
		}
	}
	
	/**
	 * Sends a lead (either to ftp or post url)
	 * @param $lead \Flux\Lead
	 * @return boolean
	 */
	function queueLead(\Flux\Lead $lead) {
		ob_start();
		\Mojavi\Util\StringTools::consoleWrite('Fulfilling Lead (' . $lead->getId() . ')', 'Finding', \Mojavi\Util\StringTools::CONSOLE_COLOR_RED);
		$this->sendLeads(array($lead));
		$ob_contents = ob_get_clean();
		return $ob_contents;
	}
	
	/**
	 * Sends a lead (either to ftp or post url)
	 * @param $lead array
	 * @return boolean
	 */
	function sendLeads(array $leads) {
		return $this->getExportClass()->send($leads);
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$client_export = new self();
		$client_export->getCollection()->ensureIndex(array('client_id' => 1), array('background' => true));
		return true;
	}

}
