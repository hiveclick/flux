<?php
// +---------------------------------------------------------------------------+
// | An absolute filesystem path to our webapp/config.php script.              |
// +---------------------------------------------------------------------------+
require_once(dirname(__FILE__) . '/config.php');

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the mojavi/mojavi.php script.              |
// +---------------------------------------------------------------------------+
require_once(MO_APP_DIR . '/mojavi.php');

// +---------------------------------------------------------------------------+
// | Hide warnings                                                             |
// +---------------------------------------------------------------------------+

// +---------------------------------------------------------------------------+
// | Create our controller. For this file we're going to use a front           |
// | controller pattern. This pattern allows us to specify module and action   |
// | GET/POST parameters and it automatically detects them and finds the       |
// | expected action.                                                          |
// +---------------------------------------------------------------------------+
$controller = \Mojavi\Controller\Controller::newInstance('\Mojavi\Controller\BasicConsoleController');

// +---------------------------------------------------------------------------+
// | Dispatch our request.                                                     |
// +---------------------------------------------------------------------------+
$_REQUEST['module'] = 'Daemon';
$_REQUEST['action'] = 'Daemon';
$arg_options = array();
$req_args = array();

$controller->dispatch($arg_options, $req_args);