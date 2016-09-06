<?php
/**
 * Ubot.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   9/1/16 10:10 AM
 */

namespace Flux\Base;


use Mojavi\Form\MongoForm;

class Ubot extends MongoForm
{

	const TYPE_COMMENT = 1;

	protected $name;
	protected $description;
	protected $url;
	protected $username;
	protected $password;
	protected $login_url;
	protected $active;
	protected $type;
	protected $script_filename;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('ubot');
		$this->setDbName('admin');
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		$this->addModifiedColumn("name");
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		if (is_null($this->description)) {
			$this->description = "";
		}
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		$this->addModifiedColumn("description");
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		if (is_null($this->url)) {
			$this->url = "";
		}
		return $this->url;
	}

	/**
	 * @param mixed $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		$this->addModifiedColumn("url");
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
	public function getLoginUrl()
	{
		if (is_null($this->login_url)) {
			$this->login_url = "";
		}
		return $this->login_url;
	}

	/**
	 * @param mixed $login_url
	 */
	public function setLoginUrl($login_url)
	{
		$this->login_url = $login_url;
		$this->addModifiedColumn("login_url");
	}

	/**
	 * @return mixed
	 */
	public function getActive()
	{
		if (is_null($this->active)) {
			$this->active = true;
		}
		return $this->active;
	}

	/**
	 * @param mixed $active
	 */
	public function setActive($active)
	{
		$this->active = (boolean)$active;
		$this->addModifiedColumn("active");
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		if (is_null($this->type)) {
			$this->type = self::TYPE_COMMENT;
		}
		return $this->type;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type)
	{
		$this->type = (int)$type;
		$this->addModifiedColumn("type");
	}

	/**
	 * @return mixed
	 */
	public function getScriptFilename()
	{
		if (is_null($this->script_filename)) {
			$this->script_filename = "";
		}
		return $this->script_filename;
	}

	/**
	 * @param mixed $script_filename
	 */
	public function setScriptFilename($script_filename)
	{
		$this->script_filename = $script_filename;
		$this->addModifiedColumn("script_filename");
	}

}