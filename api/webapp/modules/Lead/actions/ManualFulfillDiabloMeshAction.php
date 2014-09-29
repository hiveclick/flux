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
class ManualFulfillDiabloMeshAction extends BasicRestAction
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
            
            $params['xxCampaignId'] = '055nn1uj3';
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

            $params['email_address'] = $lead->getValue('email');
            $phone = $lead->getValue('phone1') .
                     $lead->getValue('phone2') .
                     $lead->getValue('phone3');
            $params['phone_home'] = $phone;

            $params['address'] = $lead->getValue('address');
            // Calculate the city and state frome the zip
            $response_obj = \Mojavi\Util\Ajax::sendAjax('/', array('zip' => $lead->getValue('zip')), \Mojavi\Request\Request::GET, 'http://zip.elevenbasetwo.com');
            $params['city'] = isset($response_obj['city']) ? $response_obj['city'] : '';
            $params['state'] = isset($response_obj['state']) ? $response_obj['state'] : '';
            $params['zip_code'] = $lead->getValue('zip');
            
            $params['ip_address'] = $lead->getTracking()->getIp();

            if ($lead->getValue('brand') != 'I don\'t know') {
                $params['case_notes'] = $lead->getValue('notes', 'Brand was ' . $lead->getValue('brand', 'Unknown'));
            }
            $params['case_notes'] = $lead->getValue('message');
            $params['attorney'] = 'No';
            
            // Convert the mesh_time to a year
            if ($lead->getValue('mesh_time') == '0-6mo') {
                $params['surgical_mesh_year'] = date('Y');
            } else if ($lead->getValue('mesh_time') == '7-12mo') {
                $params['surgical_mesh_year'] = date('Y', strtotime('now - 1 Year'));
            } else if ($lead->getValue('mesh_time') == '1-2yr') {
                $params['surgical_mesh_year'] = date('Y', strtotime('now - 2 Years'));
            } else if ($lead->getValue('mesh_time') == '3-5yr') {
                $params['surgical_mesh_year'] = date('Y', strtotime('now - 3 Years'));
            } else if ($lead->getValue('mesh_time') == '5+yr') {
                $params['surgical_mesh_year'] = date('Y', strtotime('now - 5 Years'));
            } else if (intval($lead->getValue('mesh_year')) < date('Y') && intval($lead->getValue('mesh_year')) > 2000) {
                $params['surgical_mesh_year'] = intval($lead->getValue('mesh_year'));
            } else {
                $params['surgical_mesh_year'] = date('Y', strtotime('now - 1 Year'));
            }
            
            // Convert the mesh condition
        	if ($lead->getValue('mesh_conditions') == 'POP') {
        		$params['pop'] = 1;
        	} else if ($lead->getValue('mesh_conditions') == 'Urinary Leakage') {
        		$params['sui'] = 1;
        	} else if ($lead->getValue('mesh_conditions') == 'Hernia') {
        		$params['female_unknown_reason'] = 1;        		
        	} else if ($lead->getValue('mesh_conditions') == 'Other') {
        		$params['female_unknown_reason'] = 1;
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
                if ($side_effect == 'mesh erosion' || $side_effect == 'erosion_of_mesh_into_vagina') {
                    $params['erosion_of_mesh_into_vagina'] = 1;
                } else if ($side_effect == 'infection' || $side_effect == 'infection_requiring_surgery') {
                    $params['infection_requiring_surgery'] = 1;
                } else if ($side_effect == 'pain' || $side_effect == 'vaginal_pain' || $side_effect == 'pelvic_pain') {
                    $params['vaginal_pain'] = 1;
                    $params['pelvic_pain'] = 1;
                } else if ($side_effect == 'recurrence of prolapse' || $side_effect == 'recurrence_sui_pop') {
                    $params['recurrence_sui_pop'] = 1;
                } else if ($side_effect == 'continuation of worsening of incontinence' || $side_effect == 'urinary_problems') {
                    $params['urinary_problems'] = 1;
                    $params['pelvic_pain'] = 1;
                } else if ($side_effect == 'bowel/bladder/blood vessel perforation' || $side_effect == 'bowel_bladder_blood_vessel_perforation') {
                    $params['bowel_bladder_blood_vessel_perforation'] = 1;
                } else if ($side_effect == 'vaginal scarring' || $side_effect == 'scarring' || $side_effect == 'hardening_vaginal_mesh') {
                    $params['scarring'] = 1;
                    $params['hardening_vaginal_mesh'] = 1;
                    $params['vaginal_pain'] = 1;
                } else if ($side_effect == 'need for corrective surgery' || $side_effect == 'infection_requiring_surgery' || $side_effect == 'revision_surgery_needed') {
                    $params['revision_surgery_needed'] = 1;
                    $params['infection_requiring_surgery'] = 1;
                } else if ($side_effect == 'other') {
                    $params['other'] = 1;
                } else if ($side_effect == 'none' || $side_effect == 'no_symptoms_or_injuries') {
                    $params['no_symptoms_or_injuries'] = 1;
                }
            }
             
            // Convert the revision to a Yes/No
            if (strtolower($lead->getValue('revisions')) == 'yes') {
                $params['revision_surgery_needed'] = 1;
                $params['infection_requiring_surgery'] = 1;
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
            
            \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($response_xml->reason, true));
            
            if ($response_xml->result == 'failure') {
                $errors = array();
                foreach ($response_xml->reason as $reason) {
                    $errors[] = (string)$reason;
                    $this->getErrors()->addError('error', htmlentities((string)$reason));
                }
                $this->getErrors()->addError('error', htmlentities(implode(", ", $errors)));
    			$lead->addNote('Lead unsuccessfully sent to Diablo as Mesh: ' . htmlentities(implode(", ", $errors)) . '<br /><small>' . $url . '?' . http_build_query($params, null, '&') . '</small>');
    			$lead->update();
    		} else if ($response_xml->result == 'error') {
    		    $errors = array();
                foreach ($response_xml->reason as $reason) {
                    $errors[] = (string)$reason;
                    $this->getErrors()->addError('error', htmlentities((string)$reason));
                }
    		    
    		    $this->getErrors()->addError('error', htmlentities(implode(", ", $errors)));
    		    $lead->addNote('Lead unsuccessfully sent to Diablo as Mesh: ' . htmlentities(implode(", ", $errors)) . '<br /><small>' . $url . '?' . http_build_query($params, null, '&') . '</small>');
    		    $lead->update();
		    } else if ($response_xml->result == 'success') {
    		    $lead->addNote('Lead successfully sent to Diablo as Mesh (' . $response_xml->leadId .')');
    		    $lead->setValue('fulfilled', 1);
    		    $lead->update();
    		} else if ($response_xml->result == 'queued') {
    		    $lead->addNote('Lead successfully queued to Diablo as Mesh (' . $response_xml->leadId .')');
    		    $lead->setValue('fulfilled', 1);
    		    $lead->update();
    		}
    		
        }
        return $ajax_form;
    }
}

?>