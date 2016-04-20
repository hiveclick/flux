<?php
	/**
	 * Flux API file used with Shortcodes to save information to the current lead
	 * To use, simply post form data to this url and it will be saved to the 
	 * currently loaded lead (or a new lead will be created automatically).
	 * 
	 * Responses are returned in json format
	 */
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/init.php');
    $localLead = \FluxFE\Lead::getInstance();
    $localLead->save(true);
    $response = array('result' => 'success',
    				  'record' => $localLead->toArray());
    echo json_encode($response);
?>