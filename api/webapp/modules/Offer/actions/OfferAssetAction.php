<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class OfferAssetAction extends BasicRestAction
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
     * @return \Gun\Offer
     */
    function getInputForm() {
        return new \Gun\OfferAsset();
    }
    
    /**
     * Executes an update on the offer asset
     */
    function executePut($input_form) {
        if (isset($_FILES)) {
        	foreach ($_FILES as $file) {
        		\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($_FILES, true));
        		if (isset($file['tmp_name'])) {
        			$input_form->setImageData(base64_encode(file_get_contents($file['tmp_name'])));
        		}
        	}
        }
        LoggerManager::error(__METHOD__ . " :: " . var_export($input_form, true));
        return parent::executePut($input_form);
    }
    
    /**
     * Executes an update on the offer asset
     */
    function executePost($input_form) {
        \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($_FILES, true));
    	if (isset($_FILES)) {
    		foreach ($_FILES as $file) {
    			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . var_export($_FILES, true));
    			if (isset($file['tmp_name'])) {
    				$input_form->setImageData(base64_encode(file_get_contents($file['tmp_name'])));
    			}
    		}
    	}
    	if ($input_form->getId() > 0) {
    	    return parent::executePut($input_form);
    	} else {
    	    return parent::executePost($input_form);
    	}
    }
}

?>