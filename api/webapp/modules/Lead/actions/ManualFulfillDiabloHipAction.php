<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Error\Error;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ManualFulfillDiabloHipAction extends BasicRestAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
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
        $ajax_form = new BasicAjaxForm();
        
        $lead = new \Flux\Lead();
        $lead->setId($input_form->getId());
        $lead->query();
        if (!is_null($lead->getId())) {
            $ajax_form->setRecord($lead);
            
            // Send this lead as an POST to Diablo
            $params = array();
            
            $params['xxCampaignId'] = '055nn1um2';
            $params['xxAccountId'] = '051gmrfjx';
            $params['xxTest'] = false;
            $params['subid'] = (string)$lead->getId();
            $params['publisher_id'] = '56595';
            
            if (trim($lead->getValue('name_injured')) != '') {
                $name = $lead->getValue('name_injured');
                $first_name = substr($name, 0, strpos($name, ' '));
                $last_name = substr($name, strpos($name, ' ') + 1);
            } else {
                if (strpos($lead->getValue('first_name'), ' ') !== false && trim($lead->getValue('last_name')) == '') {
                    $name = $lead->getValue('first_name');
                    $first_name = substr($name, 0, strpos($name, ' '));
                    $last_name = substr($name, strpos($name, ' ') + 1);
                } else {
                    $first_name = $lead->getValue('first_name');
                    $last_name = $lead->getValue('last_name');
                }
            }
            
            $params['first_name'] = $first_name;
            $params['last_name'] = $last_name;
            $params['hip_injured_name'] = $first_name . ' ' . $last_name;

            $params['email_address'] = $lead->getValue('email');
            $phone = $lead->getValue('ph');
            if (trim($phone) == '') {
                $phone = $lead->getValue('phone1') .
                         $lead->getValue('phone2') .
                         $lead->getValue('phone3');
            }
            $params['phone_home'] = $phone;
            $params['address'] = $lead->getValue('address');
            // Calculate the city and state frome the zip
            $response_obj = \Mojavi\Util\Ajax::sendAjax('/', array('zip' => $lead->getValue('zip')), \Mojavi\Request\Request::GET, 'http://zip.elevenbasetwo.com');
            $params['city'] = isset($response_obj['city']) ? $response_obj['city'] : '';
            $params['state'] = isset($response_obj['state']) ? $response_obj['state'] : '';
            $params['zip_code'] = $lead->getValue('zip');
            
            $params['ip_address'] = $lead->getTracking()->getIp();

            $params['case_notes'] = $lead->getValue('message');
            $params['attorney'] = 'No';
            
            // Get the year
            $params['year'] = $lead->getValue('year');
            $params['year_of_implant_surgery'] = $lead->getValue('year');
            
            if (intval($lead->getValue('year')) < 2000) {
            	$params['hip_implant_brand'] = 'hip_implanted_2000_or_before';
            }
            
            // Convert the brand
        	if ($lead->getValue('brand') == 'Stryker') {
        		$params['stryker'] = 1;
        		if (intval($lead->getValue('year')) < 2008) {
        		    $params['hip_implant_brand'] = 'stryker_implanted_2007_or_before';
        		} else {
        		    $params['hip_implant_brand'] = 'stryker_implanted_2008_or_after';
        		}
        	} else if ($lead->getValue('brand') == 'DePuy') {
        		$params['depuy'] = 1;
        		if (intval($lead->getValue('year')) > 2000) {
        			$params['hip_implant_brand'] = 'depuy_implanted_2001_or_after';
        		}
        	} else if ($lead->getValue('brand') == 'Johnson & Johnson') {
        		$params['johnson_johnson'] = 1;
        		if (intval($lead->getValue('year')) > 2000) {
        			$params['hip_implant_brand'] = 'johnson_johnson_implanted_2001_or_after';
        		}
        	} else if ($lead->getValue('brand') == 'Zimmer') {
        		$params['zimmer'] = 1;
        		if (intval($lead->getValue('year')) > 2000) {
        			$params['hip_implant_brand'] = 'zimmer_implanted_2001_or_after';
        		}
        	} else if ($lead->getValue('brand') == 'Profemur') {
        		$params['profemur'] = 1;
        		if (intval($lead->getValue('year')) > 2000) {
        			$params['hip_implant_brand'] = 'profemur_implanted_2001_or_after';
        		}
        	} else if ($lead->getValue('brand') == 'Biomet') {
        		$params['biomet'] = 1;
        	} else if ($lead->getValue('brand') == 'Other') {
        		$params['not_sure'] = 1;
        		if (intval($lead->getValue('year')) > 2000) {
        			$params['hip_implant_brand'] = 'not_sure_brand_implanted_2001_or_after';
        		}
        	} else if ($lead->getValue('brand') == 'I dont know') {
        		$params['not_sure'] = 1;
        		if (intval($lead->getValue('year')) > 2000) {
        			$params['hip_implant_brand'] = 'not_sure_brand_implanted_2001_or_after';
        		}
        	}
        	
        	
        	$side_effects = array();
        	if (is_array($lead->getValue('side_effects'))) {
        	    $side_effects = array_merge($side_effects, $lead->getValue('side_effects'));
        	} else if (is_string($lead->getValue('side_effects'))) {
        	    $side_effects[] = $lead->getValue('side_effects');
        	}
        	if (is_array($lead->getValue('effect_type'))) {
        		$side_effects = array_merge($side_effects, $lead->getValue('effect_type'));
        	} else if (is_string($lead->getValue('effect_type'))) {
        		$side_effects[] = $lead->getValue('effect_type');
        	}
            // Convert the side effects
            foreach ($side_effects as $side_effect) {
                $side_effect = trim(strtolower($side_effect));
                if ($side_effect == 'hip fracture' || $side_effect == 'hip_fracture') {
                    $params['hip_fracture'] = 1;
                    $params['fracture_failure_dislocation_loosening'] = '1';
                } else if ($side_effect == 'hip implant failure/dislocation' || $side_effect == 'implant_failure') {
                    $params['implant_failure'] = 1;
                    $params['fracture_failure_dislocation_loosening'] = '1';
                } else if ($side_effect == 'loosening of implant' || $side_effect == 'loosening_of_implant') {
                    $params['loosening_of_implant'] = 1;
                    $params['fracture_failure_dislocation_loosening'] = '1';
                } else if ($side_effect == 'metallosis (cobalt/chromium/metal blood poisoning)' || $side_effect == 'metallosis') {
                    $params['metallosis'] = 1;
                } else if ($side_effect == 'revision/replacement surgery (already done)' || $side_effect == 'revision_surgery_done') {
                    $params['revision_surgery_done'] = 1;
                } else if ($side_effect == 'revision/replacement surgery (needed/scheduled)' || $side_effect == 'revision_surgery_scheduled') {
                    $params['revision_surgery_scheduled'] = 1;
                } else if ($side_effect == 'received recall letter from doctor or surgeon' || $side_effect == 'letter_from_doctor_surgeon_manufacturer') {
                    $params['letter_from_doctor_surgeon_manufacturer'] = '1';
                    $params['hip_implant_letter'] = 1;
                } else if ($side_effect == 'received recall letter from hip manufacturer' || $side_effect == 'hip_implant_letter_manufacturer') {
                    $params['letter_from_doctor_surgeon_manufacturer'] = '1';
                    $params['hip_implant_letter_manufacturer'] = 1;
                } else if ($side_effect == 'infection at implant site requiring hospitalization' || $side_effect == 'infection_require_hospital') {
                    $params['infection_require_hospital'] = 1;
                    $params['infection_pseudotumer_alval'] = 1;
                } else if ($side_effect == 'clicking, popping or grinding' || $side_effect == 'clicking_popping_grinding') {
                    $params['clicking_popping_grinding'] = 1;
                    $params['complications'] = 1;
                } else if ($side_effect == 'swelling, extreme pain and discomfort' || $side_effect == 'swelling_pain_discomfort') {
                    //$params['swelling_pain_discomfort'] = 1;
                    $params['clicking_popping_grinding'] = 1;
                    $params['complications'] = 1;
                } else if ($side_effect == 'other') {
                    //$params['swelling_pain_discomfort'] = 1;
                    $params['clicking_popping_grinding'] = 1;
                    $params['complications'] = 1;
                } else if ($side_effect == 'none' || $side_effect == 'no_symptoms') {
                    $params['no_symptoms'] = 1;
                }
            }
            
            // Convert the lawyer to a Yes/No
            if (strtolower($lead->getValue('lawyer')) == 'yes') {
                $params['attorney'] = 'Yes';
            } else {
                $params['attorney'] = 'No';
            }
            
            $params['disclaimer'] = '1';
            $params['opt_in'] = '1';
            
                
            $url = 'https://app.leadconduit.com/v2/PostLeadAction';
            
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
                        
            $response_xml = simplexml_load_string($response->getBody());
                        
            if ($response_xml->result == 'failure') {
                $errors = array();
                foreach ($response_xml->reason as $reason) {
                    $errors[] = (string)$reason;
                    $this->getErrors()->addError('error', htmlentities((string)$reason));
                }
                $this->getErrors()->addError('error', htmlentities(implode(", ", $errors)));
    			$lead->addNote('Lead unsuccessfully sent to Diablo as Hip: ' . htmlentities(implode(", ", $errors)) . '<br /><small>' . $url . '?' . http_build_query($params, null, '&') . '</small>');
    			$lead->update();
    		} else if ($response_xml->result == 'error') {
    		    $errors = array();
                foreach ($response_xml->reason as $reason) {
                    $errors[] = (string)$reason;
                    $this->getErrors()->addError('error', htmlentities((string)$reason));
                }
    		    
    		    $this->getErrors()->addError('error', htmlentities(implode(", ", $errors)));
    		    $lead->addNote('Lead unsuccessfully sent to Diablo as Hip: ' . htmlentities(implode(", ", $errors)) . '<br /><small>' . $url . '?' . http_build_query($params, null, '&') . '</small>');
    		    $lead->update();
		    } else if ($response_xml->result == 'success') {
    		    $lead->addNote('Lead successfully sent to Diablo as Hip (' . $response_xml->leadId .')');
    		    $lead->setValue('fulfilled', 1);
    		    $lead->update();
    		} else if ($response_xml->result == 'queued') {
    		    $lead->addNote('Lead successfully queued to Diablo as Hip (' . $response_xml->leadId .')');
    		    $lead->setValue('fulfilled', 1);
    		    $lead->update();
    		}
    		
        }
        return $ajax_form;
    }
}

?>