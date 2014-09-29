<?php
use Mojavi\Action\BasicAction;
use Mojavi\View\View;
use Mojavi\Request\Request;

use Flux\Client;
use Flux\ClientExport;
// +----------------------------------------------------------------------------+
// | This file is part of the Flux package.                                      |
// |                                                                            |
// | For the full copyright and license information, please view the LICENSE    |
// | file that was distributed with this source code.                           |
// +----------------------------------------------------------------------------+
class ClientExportAction extends BasicAction
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
            try {
                /* @var $client_export Flux\ClientExport */
                $client_export = new ClientExport();
                $client_export->populate($_POST);
                $client_export->update();
                $this->getContext()->getController()->redirect('/client/client?_id=' . $client_export->getClientId());
            } catch (Exception $e) {
                $this->getErrors()->addError('error', $e->getMessage());
            }
            
            // Scan for export handlers
            $export_handlers = array();
            $files = scandir(MO_LIB_DIR . "/Flux/Export/");
            foreach ($files as $file) {
                if (strpos($file, '.') === 0) { continue; }
                if (strpos($file, 'Abstract') !== false) { continue; }
                if (trim($file) == '') { continue; }
                $class_name = substr($file, 0, strpos($file, '.php'));
                $full_class_name = '\\Flux\\Export\\' . $class_name;
                $class_instance = new $full_class_name();
                $export_handlers[$class_name] = $class_instance;
            }
            
            $this->getContext()->getRequest()->setAttribute("client", $client_export->getClient());
            $this->getContext()->getRequest()->setAttribute("client_export", $client_export);
            $this->getContext()->getRequest()->setAttribute("export_handlers", $export_handlers);
            return View::SUCCESS;
        } else {
            /* @var $client_export Flux\ClientExport */
            $client_export = new ClientExport();
            $client_export->populate($_GET);
            $client_export->query();
            
            // Scan for export handlers
            $export_handlers = array();
            $files = scandir(MO_LIB_DIR . "/Flux/Export/");
            foreach ($files as $file) {
                if (strpos($file, '.') === 0) { continue; }
                if (strpos($file, 'Abstract') !== false) { continue; }
                if (trim($file) == '') { continue; }
                $class_name = substr($file, 0, strpos($file, '.php'));
                $full_class_name = '\\Flux\\Export\\' . $class_name;
                $class_instance = new $full_class_name();
                $export_handlers[$class_name] = $class_instance;
            }

            $this->getContext()->getRequest()->setAttribute("client_export", $client_export);
            $this->getContext()->getRequest()->setAttribute("export_handlers", $export_handlers);
        }
        return View::SUCCESS;
    }
}

?>