<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class OpAction extends BasicAction
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
        // Send the cached op.js if it exists
        if (!file_exists(MO_DOCROOT_DIR . '/scripts/op.js')) {
            $op_contents = file_get_contents(MO_WEBAPP_DIR . '/meta/tracking/op.js');
            if (defined('MO_ANALYTIC_DOMAIN')) {
            	$op_contents = str_replace('%api_domain%', MO_ANALYTIC_DOMAIN, $op_contents);
            } else {
            	$op_contents = str_replace('%api_domain%', substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], '.') + 1), $op_contents);
            }
            if (is_writeable(MO_DOCROOT_DIR . '/scripts/op.js')) {
                file_put_contents(MO_DOCROOT_DIR . '/scripts/op.js', $op_contents);
            } else {
                header('Content-Type: text/javascript');
                echo $op_contents;
            }
        }
        if (file_exists(MO_DOCROOT_DIR . '/scripts/op.js')) {
            header('Location: /scripts/op.js');
        }
        return View::NONE;
    }
    
    /**
     * Indicates that this action requires security.
     *
     * @return bool true, if this action requires security, otherwise false.
     */
    public function isSecure ()
    {
    
        return false;
    
    }
}

?>