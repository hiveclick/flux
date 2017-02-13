<?php
use Mojavi\Action\BasicRestAction;
use Mojavi\Form\AjaxForm;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.									  |
// |																			|
// | For the full copyright and license information, please view the LICENSE	|
// | file that was distributed with this source code.						   |
// +----------------------------------------------------------------------------+
class PushToServerAction extends BasicRestAction
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
	 * @return \Flux\Server
	 */
	function getInputForm() {
		return new \Flux\Server();
	}

	/**
	 * Executes a GET request
	 * @param $input_form \Flux\Server
	 */
	function executePost($input_form) {
		// Handle GET Requests
		/* @var $ajax_form \Mojavi\Form\AjaxForm */
		$ajax_form = new \Mojavi\Form\AjaxForm();

		$offer = new Flux\Offer();
		$offer->setId($input_form->getOfferId());
		$offer->query();

		$offer->setFolderName($input_form->getFolderName());
		$offer->setDomainName($input_form->getDomain());

		if (\MongoId::isValid($offer->getId())) {
			$server = new Flux\Server();
			$server->setId($input_form->getId());
			$server->query();
			$server->setDocrootDir($input_form->getDocrootDir());
			$server->setForceOverwrite($input_form->getForceOverwrite());
			if (\MongoId::isValid($server->getId())) {
				if ($input_form->getFlushOfferCache()) {
					// SSH to the server and clear the APC cache
					$server->clearConfigCache($offer);
					$server->clearOfferCache($offer);
				}
				if ($input_form->getCreateSkeletonFolder()) {
					// SSH to the server and setup the basic folder path
					$server->createRemoteFolderSkeleton($offer);
					$server->recreateLibFolder($offer);
				}
				if ($input_form->getRecreateLibFolder() && !$input_form->getCreateSkeletonFolder()) {
					// SSH to the server and recreate the lib folder with any updates
					$server->recreateLibFolder($offer);
				}
				if ($input_form->getGenerateVirtualHost()) {
					// SSH to the server and setup the virtualhost in apache
					$server->generateVirtualhost($offer);
				}
			} else {
				throw new Exception('Cannot find server using id: ' . $server->getId());
			}
		} else {
			throw new Exception('Cannot find offer using id: ' . $offer->getId());
		}
		$ajax_form->setRecord($input_form);
		return $ajax_form;
	}
}

?>