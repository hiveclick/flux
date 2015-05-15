<?php
use Mojavi\Action\BasicConsoleAction;
use Mojavi\Util\StringTools;
use Mojavi\Request\Request;
use Mojavi\View\View;

/**
* Verifies that daemons are running
* @author Mark Hobson
* @since 11/27/2007 7:21 pm
*/
class UpdateAction extends BasicConsoleAction {

	const DEBUG = MO_DEBUG;

	/**
	 * Perform any execution code for this action
	 * @return integer (View::SUCCESS, View::ERROR, View::NONE)
	 */
	public function execute ()
	{
	    try {
	        if (file_exists(\Flux\Updater::UPDATE_FILE)) {
	            unlink(\Flux\Updater::UPDATE_FILE);
	        }
	        
	        /* @var $updater \Flux\Updater */
            $updater = new \Flux\Updater();
            $updater->setPercentComplete(10)->setUpdateMessage('Starting update...')->saveProgress();

            // First clean the yum repository
            $updater->setPercentComplete(15)->setUpdateMessage('Refreshing metadata...')->saveProgress();
            $cmd = '/usr/bin/yum clean metadata -q';
            $cmd_output = shell_exec($cmd);
            \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd_output);
            
            // Next install the new packages
            $updater->setPercentComplete(35)->setUpdateMessage('Installing updates...')->saveProgress();
            $cmd = 'yum update -q -y ' . \Flux\Updater::PACKAGE_NAME;
            $cmd_output = shell_exec($cmd);
            \Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: " . $cmd_output);
            
            // Next install the new packages
            $updater->setPercentComplete(100)->setUpdateMessage('Completing update')->saveProgress();
            
            // Save the newest version to the database
            $updater->checkForUpdates();
            \Flux\Preferences::savePreference('version', ($updater->getInstalledPackage()->getVersion() . '.' . $updater->getInstalledPackage()->getRelease()));
            
            // Finally clear the status file
            $updater->clearProgress();
	    } catch (\Exception $e) {
	        $updater->setPercentComplete(100)->setUpdateMessage($e->getMessage())->saveProgress();
	        $updater->clearProgress();
	    }
		return View::NONE;
	}

    /**
     * Returns the default view.  This view is used if the validation fails or if the method used in the form doesn't
     * match the list in getRequestMethods()
     * @return integer
     */
    public function getDefaultView ()
    {
    	return View::NONE;
    }

    /**
     * Sets the list of approved form methods that this action can service.
     * @return int 	-	Request::GET - Indicates that this action serves only GET requests, or...
     *             	- 	Request::POST - Indicates that this action serves only POST requests, or...
     *			- 	Request::NONE - Indicates that this action serves no requests, or...
     *			-	Request::POST | Request::GET  - Indicates that this action serves GET and POST requests
     */
    public function getRequestMethods ()
    {
        return Request::GET;
    }

    /**
     * Specifies whether the user must be authenticated (logged in) to use this action
     * @return boolean
     */
    public function isSecure()
	{
    	return false;
	}

	function isConsole() {
		return true;
	}
}
?>
