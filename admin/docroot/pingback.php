<?php

/*The URL of the blog*/
$BLOGURL = "http://dickfleming.net/";
//$BLOGURL = "http://www.lawfirm-connect.com/";

function get_response($URL, $context) {
	if(!function_exists('curl_init')) {
		die ("Curl PHP package not installedn");
	}

	/*Initializing CURL*/
	$curlHandle = curl_init();

	/*The URL to be downloaded is set*/
	curl_setopt($curlHandle, CURLOPT_URL, $URL);
	curl_setopt($curlHandle, CURLOPT_HEADER, false);
	curl_setopt($curlHandle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/601.7.7 (KHTML, like Gecko) Version/9.1.2 Safari/601.7.7');
	curl_setopt($curlHandle, CURLOPT_AUTOREFERER, true);
	curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curlHandle, CURLOPT_REFERER, 'http://www.google.com?q=');
	curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
	curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $context);

	/*Now execute the CURL, download the URL specified*/
	$response = curl_exec($curlHandle);
	return $response;
}

/*Creating the pingback.ping request
*/
function demo_pingback() {
	global $BLOGURL;
	$source_url = 'http://nutrapalcoupons.com/optin9644841';
	$remote_urls = array(
		array('http://allentownjazzfest.org/' => 'http://allentownjazzfest.org/allentown-art-museum-joins-the-allentown-jazzfest/'),
		array('http://ja.colezhu.com/' => 'http://ja.colezhu.com/support-2/chunichi_ipad'),
		//array('http://en.colezhu.com/' => 'http://en.colezhu.com/jsensei/'),
		//array('http://en.colezhu.com/' => 'http://en.colezhu.com/collins_spanish/'),
		array('http://www.tbobstattoos.com/wordpress/' => 'http://www.tbobstattoos.com/contact/'),
		array('http://www.tbobstattoos.com/wordpress/' => 'http://www.tbobstattoos.com/events/'),
		array('http://www.tbobstattoos.com/wordpress/' => 'http://www.tbobstattoos.com/faq/'),
		array('http://bxwretlind.com/blog/' => 'http://bxwretlind.com/blog/bibliography/'),
		array('http://bxwretlind.com/blog/' => 'http://bxwretlind.com/blog/who-am-i/'),
		array('http://dickfleming.net/' => 'http://dickfleming.net/bio/'),
		array('http://dickfleming.net/' => 'http://dickfleming.net/stlouis-work/'),
		array('http://dickfleming.net/' => 'http://dickfleming.net/contact/'),
		array('http://dickfleming.net/' => 'http://dickfleming.net/media/'),
		array('http://dickfleming.net/' => 'http://dickfleming.net/top-news/'),
		array('http://dickfleming.net/' => 'http://dickfleming.net/rcga-work/'),
		array('http://dickfleming.net/' => 'http://dickfleming.net/podcasts/')
	);

	// @todo add user-agent and referer to get around WordFence Firewall

	foreach ($remote_urls as $remote_site) {
		foreach ($remote_site as $blog_url => $remote_url) {
			echo "=============================================\n";
			echo $blog_url . "\n";
			echo "=============================================\n";
			$xml = array($source_url, $remote_url);
			$request = xmlrpc_encode_request("pingback.ping", $xml);
			$xmlresponse = get_response($blog_url . "/xmlrpc.php", $request);
			$response = xmlrpc_decode($xmlresponse);
			print_r($response);
			for ($i=0;$i<16;$i++) {
				echo ".";
			}
			echo "\n";
		}
	}

	//$xml = array('http://bestprobioticcoupons.com/probioticamerica?s1=6','http://dickfleming.net/bio/');
	//$xml = array('http://bestprobioticcoupons.com/probioticamerica','http://www.lawfirm-connect.com/what-are-the-rights-provided-for-drug-injury-lawyer/');
	//$request = xmlrpc_encode_request("pingback.ping", $xml);

	/*Making the request to wordpress XMLRPC of your blog*/
	//$xmlresponse = get_response($BLOGURL."/xmlrpc.php", $request);
	//$response = xmlrpc_decode($xmlresponse);

	/*Printing the response on to the console*/
	//print_r($response);
}

/*Creating the demo.sayHello request
 */
function demo_sayHello() {
	global $BLOGURL;
	$request = xmlrpc_encode_request("demo.sayHello", "");

	/*Making the request to wordpress XMLRPC of your blog*/
	$xmlresponse = get_response($BLOGURL."/xmlrpc.php", $request);
	$response = xmlrpc_decode($xmlresponse);

	/*Printing the response on to the console*/
	print_r($response);
}

demo_pingback();
echo "n";
?>