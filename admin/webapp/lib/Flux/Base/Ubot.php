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
	const TYPE_PINGOMATIC = 2;

	protected $name;
	protected $description;
	protected $url;
	protected $username;
	protected $password;
	protected $login_url;
	protected $use_hma;
	protected $active;
	protected $type;
	protected $script_filename;
	protected $rss_url;
	protected $last_rss_update_at;
	protected $urls;

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
	public function getUseHma()
	{
		if (is_null($this->use_hma)) {
			$this->use_hma = false;
		}
		return $this->use_hma;
	}

	/**
	 * @param mixed $use_hma
	 */
	public function setUseHma($use_hma)
	{
		$this->use_hma = (boolean)$use_hma;
		$this->addModifiedColumn("use_hma");
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

	/**
	 * @return mixed
	 */
	public function getRssUrl()
	{
		if (is_null($this->rss_url)) {
			$this->rss_url = "";
		}
		return $this->rss_url;
	}

	/**
	 * @param mixed $rss_url
	 */
	public function setRssUrl($rss_url)
	{
		$this->rss_url = $rss_url;
		$this->addModifiedColumn("rss_url");
	}

	/**
	 * @return mixed
	 */
	public function getLastRssUpdateAt()
	{
		if (is_null($this->last_rss_update_at)) {
			$this->last_rss_update_at = new \MongoDate();
		}
		return $this->last_rss_update_at;
	}

	/**
	 * @param mixed $last_rss_update_at
	 */
	public function setLastRssUpdateAt($last_rss_update_at)
	{
		if ($last_rss_update_at instanceof \MongoDate) {
			$this->last_rss_update_at = $last_rss_update_at;
		} else if (is_int($last_rss_update_at)) {
			$this->last_rss_update_at = new \MongoDate($last_rss_update_at);
		} else if (is_string($last_rss_update_at)) {
			$this->last_rss_update_at = new \MongoDate(strtotime($last_rss_update_at));
		}
		$this->addModifiedColumn("last_rss_update_at");
	}

	/**
	 * @return mixed
	 */
	public function getUrls()
	{
		if (is_null($this->urls)) {
			$this->urls = array();
		}
		return $this->urls;
	}

	/**
	 * @param mixed $urls
	 */
	public function setUrls($urls)
	{
		if (is_array($urls)) {
			$this->urls = array();
			foreach ($urls as $url) {
				if (trim($url) == '') { continue; }
				$this->urls[] = trim($url);
			}
		} else if (is_string($urls)) {
			if (strpos($urls, "\n") !== false) {
				$this->urls = array();
				foreach (explode("\n", $urls) as $url) {
					if (trim($url) == '') { continue; }
					$this->urls[] = trim($url);
				}
			} else if (trim($urls) != '') {
				$this->urls = array();
				$this->urls[] = trim($urls);
			}
		}
		$this->addModifiedColumn("urls");
	}
}