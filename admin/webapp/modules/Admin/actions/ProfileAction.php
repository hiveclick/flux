<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\User;
use Flux\Client;
use Mojavi\Logging\LoggerManager;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ProfileAction extends BasicAction
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
        if ($this->getContext()->getRequest()->getMethod() == Request::POST) {
            /* @var $user Flux\User */
            $user = new User();
            // Unset the image data variables because we'll update it with the $_FILES array if it exists
            if (isset($_POST['image_data'])) { unset($_POST['image_data']); }
            // Populate the user with any other changes
            $user->populate($_POST);
            // Process any uploaded files
            if (isset($_FILES)) {
                foreach ($_FILES as $file) {
                    if (trim($file['tmp_name']) != '') {
                        $user->setImageData(base64_encode(file_get_contents($file['tmp_name'])));
                        $user->addModifiedColumn('image_data');
                    }
                }
            }
            $user->update();

            $this->getContext()->getController()->redirect('/admin/profile');
        } else {
            /* @var $user Flux\User */
            $user = new User();
            $user->setId($this->getContext()->getUser()->getUserDetails()->getId());
            $user->query();

            $this->getContext()->getRequest()->setAttribute("user", $user);
        }
        return View::SUCCESS;
    }
}

?>