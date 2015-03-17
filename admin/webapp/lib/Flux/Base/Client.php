<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Client extends MongoForm {

	const CLIENT_STATUS_ACTIVE = 1;
	const CLIENT_STATUS_INACTIVE = 2;
	const CLIENT_STATUS_DELETED = 3;

	const CLIENT_TYPE_PRIMARY_ADMIN = 1;
	const CLIENT_TYPE_SECONDARY_ADMIN = 2;
	const CLIENT_TYPE_AFFILIATE = 3;

	protected $name;
	protected $email;
	protected $status;
	protected $pixel;
	protected $scrub;
	protected $reference_id;
	protected $client_type;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('client');
		$this->setDbName('admin');
	}

	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}

	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		$this->name = $arg0;
		$this->addModifiedColumn('name');
		return $this;
	}

	/**
	 * Returns the email
	 * @return string
	 */
	function getEmail() {
		if (is_null($this->email)) {
			$this->email = "";
		}
		return $this->email;
	}

	/**
	 * Sets the email
	 * @var string
	 */
	function setEmail($arg0) {
		$this->email = $arg0;
		$this->addModifiedColumn('email');
		return $this;
	}

	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::CLIENT_STATUS_ACTIVE;
		}
		return $this->status;
	}

	/**
	 * Sets the status
	 * @var integer
	 */
	function setStatus($arg0) {
		$this->status = (int)$arg0;
		$this->addModifiedColumn('status');
		return $this;
	}

	/**
	 * Returns the pixel
	 * @return string
	 */
	function getPixel() {
		if (is_null($this->pixel)) {
			$this->pixel = "";
		}
		return $this->pixel;
	}

	/**
	 * Sets the pixel
	 * @var string
	 */
	function setPixel($arg0) {
		$this->pixel = $arg0;
		$this->addModifiedColumn('pixel');
		return $this;
	}

	/**
	 * Returns the scrub
	 * @return float
	 */
	function getScrub() {
		if (is_null($this->scrub)) {
			$this->scrub = 0.0;
		}
		return $this->scrub;
	}

	/**
	 * Sets the scrub
	 * @var float
	 */
	function setScrub($arg0) {
		$this->scrub = (float)$arg0;
		$this->addModifiedColumn('scrub');
		return $this;
	}

	/**
	 * Returns the reference_id
	 * @return string
	 */
	function getReferenceId() {
		if (is_null($this->reference_id)) {
			$this->reference_id = "";
		}
		return $this->reference_id;
	}

	/**
	 * Sets the reference_id
	 * @var string
	 */
	function setReferenceId($arg0) {
		$this->reference_id = $arg0;
		$this->addModifiedColumn('reference_id');
		return $this;
	}

	/**
	 * Returns the client_type
	 * @return integer
	 */
	function getClientType() {
		if (is_null($this->client_type)) {
			$this->client_type = self::CLIENT_TYPE_PRIMARY_ADMIN;
		}
		return $this->client_type;
	}

	/**
	 * Sets the client_type
	 * @var integer
	 */
	function setClientType($arg0) {
		$this->client_type = (int)$arg0;
		$this->addModifiedColumn('client_type');
		return $this;
	}

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
}
