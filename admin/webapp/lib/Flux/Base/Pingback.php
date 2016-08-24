<?php
/**
 * Pingback.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   8/16/16 8:31 AM
 */

namespace Flux\Base;


use Mojavi\Form\MongoForm;

class Pingback extends MongoForm
{
	protected $name;
	protected $description;
	protected $rpc_url;
	protected $url;
	protected $active;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('pingback');
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
	public function getRpcUrl()
	{
		if (is_null($this->rpc_url)) {
			$this->rpc_url = "";
		}
		return $this->rpc_url;
	}

	/**
	 * @param mixed $rpc_url
	 */
	public function setRpcUrl($rpc_url)
	{
		$this->rpc_url = $rpc_url;
		$this->addModifiedColumn("rpc_url");
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



}