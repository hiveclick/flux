<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class TAction extends BasicAction
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
    	echo "SUCCESS";
		return View::NONE;
    }

    // -------------------------------------------------------------------------

    /**
     * Retrieve the default view to be executed when a given request is not
     * served by this action.
     * @return mixed - A string containing the view name associated with this action
     */
    public function getDefaultView ()
    {
		return View::NONE;
    }

    // -------------------------------------------------------------------------

    /**
     * Retrieve the request methods on which this action will process
     * validation and execution.
     *
     * @return int - Request::GET - Indicates that this action serves only GET
     *               requests, or...
     *             - Request::POST - Indicates that this action serves only POST
     *               requests, or...
     *             - Request::NONE - Indicates that this action serves no
     *               requests, or...
     */
    public function getRequestMethods ()
    {
        return Request::GET | Request::POST;
    }

    /**
     * Retrieve the request methods on which this action will process
     * validation and execution.
     */
    public function isSecure ()
    {
    	return false;
    }
}

?>