<?php
namespace Flux;

class Zip extends Base\Zip {

	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
    /**
     * Looks up a city/state based on the zipcode
     * @param \Flux\Zip $zip
     */
    static function lookupState($zip_code = "") {
        if (($result = self::lookup($zip_code)) !== false) {
            return $result->getStateAbbreviation();
        }
        return "";
    }
    
    /**
     * Looks up a city/state based on the zipcode
     * @param \Flux\Zip $zip
     */
    static function lookupCity($zip_code = "") {
        if (($result = self::lookup($zip_code)) !== false) {
            return $result->getCity();
        }
        return "";
    }
	
	/**
	 * Looks up a city/state based on the zipcode
	 * @param \Flux\Zip $zip
	 */
	static function lookup($zip_code = "") {
	    $zip = new \Flux\Zip();
	    $zip->setZipcode($zip_code);
	    $zip_result = $zip->queryByZipcode();
	    return $zip_result;
	}
	
	/**
	 * Queries by the zipcode
	 * @return \Flux\Zip
	 */
	function queryByZipcode() {
	    $criteria = array();
	    $criteria['zipcode'] = $this->getZipcode();
	    return parent::query($criteria, false);
	}
	
	/**
	 * Queries a report column by it's name
	 * @return Flux\ReportColumn
	 */
	function queryAll(array $criteria = array(), $hydrate = true, $fields = array()) {
	    if (trim($this->getKeywords()) != '') {
	        $search_params = array();
	        $search_params[] = array('zipcode' => new \MongoRegex("/" . $this->getKeywords() . "/i"));
	        $search_params[] = array('city' => new \MongoRegex("/" . $this->getKeywords() . "/i"));
	        $search_params[] = array('state' => new \MongoRegex("/" . $this->getKeywords() . "/i"));
	        $criteria['$or'] = $search_params;
	    }
	    return parent::queryAll($criteria, $hydrate, $fields);
	}
	
	/**
	 * Download the updated zipcode file
	 * @param $filename
	 * @return $filename
	 */
	function downloadZipcodeFile($filename = null) {
	    try {
    	    $bg_progress = new \Flux\BackgroundProgress('/tmp/zipcode_update.json');
    	    $bg_progress->setProgress(5)->setMessage('Checking for updates...')->save();
    	    if (is_null($filename)) {
    	        $filename = tempnam("/tmp", "zipcode");
    	    }
    	    $tmp_zip = tempnam("/tmp", "zipcode");
    	    
    	    if (($fh = fopen('http://download.geonames.org/export/zip/US.zip', 'rb')) !== false) {
    	        if (($fhw = fopen($tmp_zip, 'w')) !== false) {
    	            while (($buffer = stream_get_contents($fh)) != false) {
    	                fwrite($fhw, $buffer);
    	            }
    	            clearstatcache();
    	            $file_size = filesize($tmp_zip);
    	            $bg_progress->setProgress(20)->setMessage('Downloaded zipcode data...' . number_format($file_size, 0, null, ',') . ' bytes')->save();
    	            fclose($fhw);
    	        } else {
    	            throw new \Exception('Cannot open temporary zip ' . $tmp_zip . ' for writing');
    	        }
    	        fclose($fh);
    	    } else {
                throw new \Exception('Cannot open http://download.geonames.org/export/zip/US.zip for reading');
            }
            $bg_progress->setProgress(25)->setMessage('Extracting zip files...')->save();
    	    // We should now have a zip file, so we need to open it
    	    $zh = zip_open($tmp_zip);
    	    if (is_resource($zh)) {
    	        if (($fhw = fopen($filename, 'w')) !== false) {
        	        while (($zh_entry = zip_read($zh)) !== false) {
        	            if (zip_entry_open($zh, $zh_entry, "r")) {
        	                if (zip_entry_name($zh_entry) == 'US.txt') {
                                while (($buffer = zip_entry_read($zh_entry, 4096)) != false) {
                                    fwrite($fhw, $buffer);
                                }
                                clearstatcache();
                                $file_size = filesize($filename);
                                $bg_progress->setProgress(45)->setMessage('Extracting US zip codes...')->save();
        	                }
        	                zip_entry_close($zh_entry);
        	            }
        	        }
        	        fclose($fhw);
    	        } else {
    	            throw new \Exception('Cannot open ' . $filename . ' for writing');
    	        }
    	    } else {
    	        throw new \Exception('Cannot open zip file ' . $tmp_zip . ' for reading');
    	    }
    	    zip_close($zh);
    	    
    	    // Calculate the # of lines we need to import
    	    $cmd = 'wc -l ' . $filename;
    	    $total_lines = intval(shell_exec($cmd));
    	    
    	    // Now that we have a zipcode file, let's update the zipcodes
    	    // Preparing to read file
    	    if (($fh = fopen($filename,'r')) !== false) {
    	        $counter = 0;
    	        while (($data = fgetcsv($fh, 0, "\t")) !== false) {
    	            if (isset($data[0]) && isset($data[1])) {
    	                //right now we only do US and CA
    	                if (in_array($data[0], array('US', 'CA'))) {
    	                    $zip = new \Flux\Zip();
    	                    $zip->setCountry($data[0]);
    	                    $zip->setZipcode(isset($data[1]) ? $data[1] : '');
    	                    $zip->setCity(isset($data[2]) ? $data[2] : '');
    	                    $zip->setState(isset($data[3]) ? $data[3] : '');
    	                    $zip->setStateAbbreviation(isset($data[4]) ? $data[4] : '');
    	                    $zip->setCounty(isset($data[5]) ? $data[5] : '');
    	                    $zip->setCountyAbbreviation(isset($data[6]) ? $data[6] : '');
    	                    $zip->setCommunity(isset($data[7]) ? $data[7] : '');
    	                    $zip->setCommunityAbbreviation(isset($data[8]) ? $data[8] : '');
    	                    $zip->setLatitude(isset($data[9]) ? $data[9] : 0);
    	                    $zip->setLongitude(isset($data[10]) ? $data[10] : 0);
    	                    $zip->setAccuracy(isset($data[11]) ? $data[11] : 1);
    	                    $update_array = $zip->toArray();
    	                    // Unset the _id field
    	                    unset($update_array['_id']);
    	                    unset($update_array['id']);
    	                    
    	                    $zip->updateMultiple(array('zipcode' => $zip->getZipcode()), array('$set' => $update_array), array('upsert' => true));
    	                    $counter++;
    	                    if ($counter % 100 == 0) {
    	                        $bg_progress->setProgress(45 + (55 * ($counter / $total_lines)))->setMessage('Updating zipcodes (' . $counter . '/' . $total_lines . ')...')->save();	                        
    	                    }
    	                }
    	            } else {
    	                throw new \Exception('Column 0 or Column 1 not found');
    	            }
    	        }
    	        fclose($fh);
    	    } else {
    	        throw new \Exception('Could not open file US.txt');
    	    }
    	    
    	    $bg_progress->setProgress(100)->setMessage('Update Complete')->setIsComplete(true)->save();
	    } catch (\Exception $e) {
	        $bg_progress->setProgress(100)->setMessage($e->getMessage())->setIsComplete(true)->save();
	    }
	    
	    return $filename;
	}
}
