<?php
/**
 * Pingback.php is a part of the flux project.
 *
 * @link      http://github.com/hiveclick/buxbux for the canonical source repository
 * @copyright Copyright (c) 2010-2020 BuxBux USA Inc. (http://www.bux-bux.com)
 * @license   http://github.com/hiveclick/buxbux/license.txt
 * @author    hobby
 * @created   8/16/16 8:33 AM
 */

namespace Flux;


class Pingback extends Base\Pingback
{
	protected $rpc_response;

	/**
	 * @return mixed
	 */
	public function getRpcResponse()
	{
		if (is_null($this->rpc_response)) {
			$this->rpc_response = "";
		}
		return $this->rpc_response;
	}

	/**
	 * @param mixed $rpc_response
	 */
	public function setRpcResponse($rpc_response)
	{
		$this->rpc_response = $rpc_response;
		$this->addModifiedColumn("rpc_response");
	}

	/**
	 * Attempts to say hello and returns the result
	 */
	function sayHello() {
		$request = xmlrpc_encode_request("demo.sayHello", "");
		$xmlresponse = $this->sendXmlRpc($this->getRpcUrl(), $request);
		$response = xmlrpc_decode($xmlresponse);
		$this->setRpcResponse($response);
		return $response;
		// parse the response
		/*
		 * <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
		 * <methodResponse>
		 *   <params>
		 * 		<param>
		 * 		<value>
		 * 			<string>Hello!</string>
		 * 		</value>
		 * 		</param>
		 * 	</params>
		 * </methodResponse>
		 */
	}

	/**
	 * Sends the XML RPC Request and parses the response
	 * @param $url string
	 * @param $request array
	 */
	function sendXmlRpc($url, $request) {
		/*Initializing CURL*/
		$curlHandle = curl_init();

		$user_agents = array(
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/601.7.7 (KHTML, like Gecko) Version/9.1.2 Safari/601.7.7',
			'Mozilla/5.0 (compatible, MSIE 11, Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko',
			'Mozilla/4.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)',
			'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))',
			'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0; chromeframe/11.0.696.57)',
			'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1',
			'Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201',
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A',
			'Mozilla/5.0 (X11; Linux) KHTML/4.9.1 (like Gecko) Konqueror/4.9',
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.1 Safari/537.36'
		);
		shuffle($user_agents);
		$user_agent = array_pop($user_agents);

		$referrers = array(
			'http://www.google.com?q=',
			'http://www.google.com',
			'http://www.yahoo.com',
			'http://www.bing.com'
		);
		shuffle($referrers);
		$referrer = array_pop($referrers);

		/*The URL to be downloaded is set*/
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, false);
		curl_setopt($curlHandle, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($curlHandle, CURLOPT_AUTOREFERER, true);
		curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curlHandle, CURLOPT_REFERER, $referrer);
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $request);

		/*Now execute the CURL, download the URL specified*/
		$response = curl_exec($curlHandle);
		return $response;
	}

}