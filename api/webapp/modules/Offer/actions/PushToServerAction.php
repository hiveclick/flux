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
class PushToServerAction extends BasicRestAction
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
     * @return \Gun\Server
     */
    function getInputForm() {
        return new \Gun\Server();
    }

    /**
     * Executes a GET request
     * @param $input_form \Gun\Server
     */
    function executePost($input_form) {
        // Handle GET Requests
        /* @var $ajax_form BasicAjaxForm */
        $ajax_form = new BasicAjaxForm();

        $offer = new Gun\Offer();
        $offer->setId($input_form->getOfferId());
        $offer->query();

        $offer->setFolderName($input_form->getFolderName());
        $offer->setDomainName($input_form->getDomain());

        if ($offer->getId() > 0) {
            $server = new Gun\Server();
            $server->populate($_REQUEST);
            $server->query();
            if ($server->getId() > 0) {
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
                throw new Exception('Cannot find server using id: ' . $input_form->getServerId());
            }
        } else {
            throw new Exception('Cannot find offer using id: ' . $input_form->getId());
        }
        $ajax_form->setRecord($input_form);
        return $ajax_form;
    }
}

?>