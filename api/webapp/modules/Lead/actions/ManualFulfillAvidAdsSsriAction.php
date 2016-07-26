<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class ManualFulfillAvidAdsSsriAction extends BasicRestAction
{

	// +-----------------------------------------------------------------------+
	// | METHODS															   |
	// +-----------------------------------------------------------------------+

	/**
	 * Execute any application/business logic for this action.
	 * @return mixed - A string containing the view name associated with this action
	 */
	public function execute ()
	{
		return parent::execute();
	}

	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function getInputForm() {
		return new \Flux\Lead();
	}
	
	/**
	 * Returns the input form to use for this rest action
	 * @return \Flux\Offer
	 */
	function executePost($input_form) {
		$ajax_form = new \Mojavi\Form\AjaxForm();
		
		$lead = new \Flux\Lead();
		$lead->setId($input_form->getId());
		$lead->query();
		if (!is_null($lead->getId())) {
			$ajax_form->setRecord($lead);
			
			// Send this lead as an POST to Howard East
			$params = array();
			
			$params['campaign_id'] = 481;
			$params['posting_token'] = 'c262ba5acd2ba25fbb40fea856c16d49';
			
			$name = $lead->getValue('first_name');
			$first_name = substr($name, 0, strpos($name, ' '));
			$last_name = substr($name, strpos($name, ' ') + 1);
			
			$params['first_name'] = $first_name;
			$params['last_name'] = $last_name;
			$params['email_address'] = $lead->getValue('email');
			$phone = $lead->getValue('phone1');
			$params['home_phone'] = $phone;
			$params['address_1'] = $lead->getValue('address');
			// Calculate the city and state frome the zip
			$response_obj = \Mojavi\Util\Ajax::sendAjax('/', array('zip' => $lead->getValue('zip')), \Mojavi\Request\Request::GET, 'http://zip.elevenbasetwo.com');
			$params['city'] = isset($response_obj['city']) ? $response_obj['city'] : '';
			$params['state'] = isset($response_obj['state']) ? $response_obj['state'] : '';
			$params['zip'] = $lead->getValue('zip');
			
			$params['notes'] = $lead->getValue('notes', 'no notes');
			$params['ip_address'] = $lead->getValue('ip_address');
			$params['datetime'] = date('Y-m-d G:i:s', $lead->getValue(\Flux\DataField::DATA_FIELD_EVENT_CREATED_NAME, new \MongoDate())->sec);
			$params['has_birth_defect'] = 1;
			$params['has_attorney'] = 0;
			$params['medication'] = implode(",", $lead->getValue('medication_type'));
			$params['birth_defects'] = implode(",", $lead->getValue('defect_type'));
			$params['year_of_birth'] = $lead->getValue('birthyear');
   
			$url = 'http://intake.avidads.com/lead.php';
			
			// gots to do this so that transfer-encoding: chunked comes through properly
			$curl_adapter = new \Zend\Http\Client\Adapter\Curl();
			// Enforce a 30 second timeout (default is unlimited)
			$curl_adapter->setCurlOption(CURLOPT_TIMEOUT, 60);
			$curl_adapter->setCurlOption(CURLOPT_CONNECTTIMEOUT, 60);
			$curl_adapter->setCurlOption(CURLOPT_ENCODING , "gzip");
			$curl_adapter->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
			$curl_adapter->setCurlOption(CURLOPT_USERAGENT, 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10');
			$http_client = new \Zend\Http\Client();
			$http_client->setAdapter($curl_adapter);
			$http_client->setUri($url);
			$http_client->setArgSeparator('&');
			
			$request = new \Zend\Http\Request();
			$request->setUri($url);
			
			$request->getHeaders()->addHeaderLine('Accept-Encoding', 'gzip,deflate');
			$request->getHeaders()->addHeaderLine('Content-Type', \Zend\Http\Client::ENC_URLENCODED);
			
			if (is_array($params)) { $request->getPost()->fromArray($params); }
			$request->setMethod(\Zend\Http\Request::METHOD_POST);
			
			$response = $http_client->send($request);
			
			if (strpos($response->getBody(), 'Good') === false) {
				$this->getErrors()->addError('error', $response->getBody());
				$lead->addNote('Lead unsuccessfully sent to Avid Ads as SSRI: ' . $response->getBody() . '<br /><small>' . $url . '?' . http_build_query($params, null, '&') . '</small>');
				$lead->update();
			} else {
				$lead->addNote('Lead successfully sent to Avid Ads as SSRI');
				$lead->setValue('fulfilled', 1);
				$lead->update();
			}
		}
		return $ajax_form;
	}
}

?>