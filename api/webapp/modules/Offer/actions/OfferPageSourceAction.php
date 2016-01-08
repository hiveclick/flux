<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\BasicAjaxForm;
use Mojavi\Logging\LoggerManager;
use Flux\Server;
use Flux\OfferPage;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class OfferPageSourceAction extends BasicRestAction
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
		return new \Flux\OfferPage();
	}

	/**
	 * Executes a GET request
	 */
	function executePost($input_form) {
		// Handle POST Requests
		/* @var $ajax_form BasicAjaxForm */
		$ajax_form = new BasicAjaxForm();
	
		/* @var $offer_page \Flux\OfferPage */
		$offer_page = new OfferPage();
		$offer_page->setId($input_form->getId());
		$offer_page->query();
		
		$offer_page->setPageSource($input_form->getPageSource());
	
		/* @var $server \Flux\Server */
		$server = new Server();
		$server->setId($input_form->getServerId());
		$server->query();
		try {
			if (($file_contents = $server->writeRemoteFile($input_form->getPageSource(), $input_form->getFilePath(), 0777)) === false) {
				throw new \Exception('Cannot write remote file ' . $input_form->getFilePath());
			}
		} catch (\Exception $e) {
			$this->getErrors()->addError('error', $e->getMessage());
		}
		$ajax_form->setRecord($offer_page);
		$ajax_form->setRowsAffected(1);
		return $ajax_form;
	}
	
	/**
	 * Executes a GET request
	 */
	function executeGet($input_form) {
		// Handle GET Requests
		/* @var $ajax_form BasicAjaxForm */
		$ajax_form = new BasicAjaxForm();
		
		/* @var $offer_page \Flux\OfferPage */
		$offer_page = new OfferPage();
		$offer_page->setId($input_form->getId());
		$offer_page->query();
		
		/* @var $server \Flux\Server */
		$server = new Server();
		$server->setId($input_form->getServerId());
		$server->query();
		try {
			if (($file_contents = $server->readRemoteFile($input_form->getFilePath())) !== false) {
				$offer_page->setPageSource($file_contents);
			} else {
				throw new \Exception('Cannot retrieve remote file ' . $input_form->getFilePath());
			}
		} catch (\Exception $e) {
			$this->getErrors()->addError('error', $e->getMessage());
		}
		$ajax_form->setRecord($offer_page);
		
		return $ajax_form;
	}
}

?>