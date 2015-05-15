<?php
use \Mojavi\Action\BasicRestAction;

/**
* SettingAction goes to a page allowing you to use REST verbs for the setting table.
*  
* @author Mark Hobson 
* @since 02/10/2012 10:36 pm 
*/
class PreferencesAction extends BasicRestAction {

	const DEBUG = MO_DEBUG;

	/**
	 * Returns the form to use for this rest request
	 * @return Form
	 */
	public function getInputForm() {
		return new \Flux\Preferences();
	}

	/**
	 * Perform any execution code for this action
	 * @return integer (View::SUCCESS, View::ERROR, View::NONE)
	 */
	public function execute ()
	{
		return parent::execute();
	}
} 
?>