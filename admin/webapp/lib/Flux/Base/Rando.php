<?php
/**
 * Rando.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   9/16/16 11:04 AM
 */

namespace Flux\Base;


use Mojavi\Form\MongoForm;

class Rando extends MongoForm
{
	protected $fname;
	protected $lname;
	protected $address;
	protected $city;
	protected $state;
	protected $zip;
	protected $phone;
	protected $email;
	protected $ssn;
	protected $birthdate;
	protected $username;
	protected $password;
	protected $browser;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('rando');
		$this->setDbName('admin');
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->getFname() . ' ' . $this->getLname();
	}

	/**
	 * @return mixed
	 */
	public function getFname()
	{
		if (is_null($this->fname)) {
			$this->fname = "";
		}
		return $this->fname;
	}

	/**
	 * @param mixed $fname
	 */
	public function setFname($fname)
	{
		$this->fname = $fname;
		$this->addModifiedColumn("fname");
	}

	/**
	 * @return mixed
	 */
	public function getLname()
	{
		if (is_null($this->lname)) {
			$this->lname = "";
		}
		return $this->lname;
	}

	/**
	 * @param mixed $lname
	 */
	public function setLname($lname)
	{
		$this->lname = $lname;
		$this->addModifiedColumn("lname");
	}

	/**
	 * @return mixed
	 */
	public function getAddress()
	{
		if (is_null($this->address)) {
			$this->address = "";
		}
		return $this->address;
	}

	/**
	 * @param mixed $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
		$this->addModifiedColumn("address");
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		if (is_null($this->city)) {
			$this->city = "";
		}
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
		$this->addModifiedColumn("city");
	}

	/**
	 * @return mixed
	 */
	public function getState()
	{
		if (is_null($this->state)) {
			$this->state = "";
		}
		return $this->state;
	}

	/**
	 * @param mixed $state
	 */
	public function setState($state)
	{
		$this->state = $state;
		$this->addModifiedColumn("state");
	}

	/**
	 * @return mixed
	 */
	public function getZip()
	{
		if (is_null($this->zip)) {
			$this->zip = "";
		}
		return $this->zip;
	}

	/**
	 * @param mixed $zip
	 */
	public function setZip($zip)
	{
		$this->zip = $zip;
		$this->addModifiedColumn("zip");
	}

	/**
	 * @return mixed
	 */
	public function getPhone()
	{
		if (is_null($this->phone)) {
			$this->phone = "";
		}
		return $this->phone;
	}

	/**
	 * @param mixed $phone
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
		$this->addModifiedColumn("phone");
	}

	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		if (is_null($this->email)) {
			$this->email = "";
		}
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		$this->addModifiedColumn("email");
	}

	/**
	 * @return mixed
	 */
	public function getSsn()
	{
		if (is_null($this->ssn)) {
			$this->ssn = "";
		}
		return $this->ssn;
	}

	/**
	 * @param mixed $ssn
	 */
	public function setSsn($ssn)
	{
		$this->ssn = $ssn;
		$this->addModifiedColumn("ssn");
	}

	/**
	 * @return mixed
	 */
	public function getBirthdate()
	{
		if (is_null($this->birthdate)) {
			$this->birthdate = new \MongoDate();
		}
		return $this->birthdate;
	}

	/**
	 * @param mixed $birthdate
	 */
	public function setBirthdate($birthdate)
	{
		if ($birthdate instanceof \MongoDate) {
			$this->birthdate = $birthdate;
		} else if (is_int($birthdate)) {
			$this->birthdate = new \MongoDate($birthdate);
		} else if (is_string($birthdate)) {
			$this->birthdate = new \MongoDate(strtotime($birthdate));
		}
		$this->addModifiedColumn("birthdate");
	}

	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		if (is_null($this->username)) {
			$this->username = "";
		}
		return $this->username;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		$this->addModifiedColumn("username");
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		if (is_null($this->password)) {
			$this->password = "";
		}
		return $this->password;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		$this->addModifiedColumn("password");
	}

	/**
	 * @return mixed
	 */
	public function getBrowser()
	{
		if (is_null($this->browser)) {
			$this->browser = "";
		}
		return $this->browser;
	}

	/**
	 * @param mixed $browser
	 */
	public function setBrowser($browser)
	{
		$this->browser = $browser;
		$this->addModifiedColumn("browser");
	}
}