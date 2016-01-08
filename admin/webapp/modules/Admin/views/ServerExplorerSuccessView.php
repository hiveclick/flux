<?php
/**
* DomainGroupsSuccessView goes to a page allowing you to view an account
* Stores a list of accounts in the system 
* @author Mark Hobson 
* @since 11/27/2007 7:21 pm 
*/

require_once(MO_MODULE_DIR . "/Default/views/IndexBlankView.php");

class ServerExplorerSuccessView extends IndexBlankView {

	/**
	 * Perform any execution code for this action
	 * @return void
	 */
	public function execute ()
	{
		parent::execute();
		
		// set our template
		$this->setTemplate("ServerExplorerSuccess.php");

		// set the title
		$this->setTitle("Server Explorer");
	}
} 
?>