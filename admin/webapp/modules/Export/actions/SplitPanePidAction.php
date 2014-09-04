<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Gun\Split;
use Gun\Offer;
use Gun\Vertical;
use Gun\Client;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class SplitPanePidAction extends BasicAction
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
		/* @var $split Gun\Split */
		$split = new Split();
		$split->populate($_GET);
		$split->query();
            
		$this->getContext()->getRequest()->setAttribute("split", $split);
        
        return View::SUCCESS;
    }
}

?>