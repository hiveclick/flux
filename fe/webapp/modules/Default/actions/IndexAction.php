<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

class IndexAction extends BasicAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Execute any application/business logic for this action.
     *
     * @return mixed - A string containing the view name associated with this action
     */
    public function execute ()
    {
		return View::SUCCESS;
        // we don't need any data here because this action doesn't serve
        // any request methods, so the processing skips directly to the view
		

    }

    // -------------------------------------------------------------------------

    /**
     * Retrieve the default view to be executed when a given request is not
     * served by this action.
     *
     * @return mixed - A string containing the view name associated with this action
     */
    public function getDefaultView ()
    {
		return View::SUCCESS;
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
        return Request::GET;
    }
}
