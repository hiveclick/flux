<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Error\Error;
use Gun\User;
// +----------------------------------------------------------------------------+
// | This file is part of the Gun package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class LoginAction extends BasicAction
{

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+
    const DEBUG = true; //MO_DEBUG;
    /**
     * Execute any application/business logic for this action.
     *
     * @return mixed - A string containing the view name associated with this
     *                 action, or...
     *               - An array with three indices:
     *                 0. The parent module of the view that will be executed.
     *                 1. The parent action of the view that will be executed.
     *                 2. The view that will be executed.
     */
    public function execute ()
    {
        /* @var $user Gun\User */
        $user = new User();
        $user->populate($_POST);
        $is_cookie_login = false;
        
        /*
        // Check if we have a cookie set
        if (isset($_COOKIE['_' . strtolower(MO_APP_NAME) . "_cookie"])) {
            $session_id = $_COOKIE['_' . strtolower(MO_APP_NAME) . "_cookie"];
            $account_user_form = new DaoAccount_Form_SuperAccountUser();
            $account_user_form->setId($session_id);
            $account_user_form->query();
            
            if ($account_user_form->getId() > 0 && $account_user_form->isActive() && !$account_user_form->IsDeleted()) {
                $this->getContext()->getUser()->setAuthenticated(true);
                $this->getContext()->getUser()->setUserDetails($account_user_form);
                setcookie("track_login_session", (string)$account_user_form->getEncodedId(), (time() + 259200), "/", false);
                $is_cookie_login = true;
            }
        }
        */
        
        // Only login if we are not authenticated
        if (!$is_cookie_login) {
            if (strlen($user->getEmail()) == 0 || strlen($user->getPassword()) == 0) {
                return View::SUCCESS;
            } else {
                /* @var $user Gun\User */
                try {
                    $user->tryLogin();
                } catch (\Exception $e) {
                    error_log('tryLogin error: ' . $e->getMessage());
                }
                
                if ($user->getId() <= 0) {
                    $this->getErrors()->addError("error", new Error("Your login credentials could not be validated. Please try again."));
                } else if (!$user->isActive()) {
                    $this->getErrors()->addError("error", new Error("Your account is not currently active. Please contact customer service to re-activate your account."));
                }
            }
        }
                
        if ($this->getErrors()->isEmpty()) {
            
            if (!$is_cookie_login) {
                $this->getContext()->getUser()->setUserDetails($user);
                $this->getContext()->getUser()->setAuthenticated(true);
                setcookie('_' . strtolower(MO_APP_NAME) . "_cookie", (string)$user->getId(), (time() + 259200), "/", false);
            }

            /*
            if (self::DEBUG) { LoggerManager::debug(__METHOD__ . " :: " . "Resetting previous Permission Container..."); }
            DaoPermission_PermissionContainer::getInstance()->reset();
            
            if (defined("DaoPermission_Interface_PermissionLinkType::PERMISSION_LINK_TYPE_ACCOUNT_USER")) {
                if (self::DEBUG) { LoggerManager::debug(__METHOD__ . " :: " . "Adding Account User Link Type: " . $account_user_form->getAccountUserId()); }
                DaoPermission_PermissionContainer::getInstance()->addLinkType(DaoPermission_Interface_PermissionLinkType::PERMISSION_LINK_TYPE_ACCOUNT_USER, $account_user_form->getAccountUserId());
            }
            
            if (defined("DaoPermission_Interface_PermissionLinkType::PERMISSION_LINK_TYPE_ACCOUNT")) {
                if (self::DEBUG) { LoggerManager::debug(__METHOD__ . " :: " . "Adding Account Link Type: " . $account_user_form->getAccountId()); }
                DaoPermission_PermissionContainer::getInstance()->addLinkType(DaoPermission_Interface_PermissionLinkType::PERMISSION_LINK_TYPE_ACCOUNT, $account_user_form->getAccountId());
            }
            
            if (defined("DaoPermission_Interface_PermissionLinkType::PERMISSION_LINK_TYPE_USER_TYPE")) {
                if (self::DEBUG) { LoggerManager::debug(__METHOD__ . " :: " . "Adding User Type Link Type: " . $account_user_form->getUserTypeId()); }
                DaoPermission_PermissionContainer::getInstance()->addLinkType(DaoPermission_Interface_PermissionLinkType::PERMISSION_LINK_TYPE_USER_TYPE, $account_user_form->getUserTypeId());
            }
            
            foreach (DaoPermission_PermissionContainer::getInstance()->getPermissionLinkTypeArray() as $permission_link_type) {
                if (self::DEBUG) { LoggerManager::debug(__METHOD__ . " :: " . "Link Type (" . $permission_link_type->getPermissionLinkTypeId() . "): " . $permission_link_type->getLinkId()); }
            }
            */
        }

        if ($this->getErrors()->isEmpty()) {
            if ($user->getForward() != "") {
                if ((strpos($user->getForward(), "Login") === false) && (strpos($user->getForward(), "Ajax") === false)) {
                    $this->getContext()->getController()->redirect($user->getForward());
                } else {
                    $this->getContext()->getController()->redirect("/index");
                }
            } else {
                $this->getContext()->getController()->redirect("/index");
            }
            return View::NONE;
        } else {
            return View::SUCCESS;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Retrieve the default view to be executed when a given request is not
     * served by this action.
     *
     * @return mixed - A string containing the view name associated with this
     *                 action, or...
     *               - An array with three indices:
     *                 0. The parent module of the view that will be executed.
     *                 1. The parent action of the view that will be executed.
     *                 2. The view that will be executed.
     */
    public function getDefaultView ()
    {
        return View::SUCCESS;
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
