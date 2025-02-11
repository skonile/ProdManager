<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;
use App\Plugins\BasePlugin;
use App\Plugins\PluginManager;
use App\FormBuilder\FormBuilder;
use App\Controllers\BaseController;

use App\Exceptions\PluginException;

class PluginsController extends BaseController{
    /**
     * Get Plugins.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function getPlugins(Request $request, Response $response){
        $plug = PluginManager::getInstance();
        $plug->loadPlugins();
        $this->render(
            'plugins/plugins', 
            [
                'route' => $request->getFirstUriPart(), 
                'plugins' => $plug->getPlugins()
            ]
        );
    }

    /**
     * Get a given Plugin.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function getPlugin(Request $request, Response $response){
        $pluginManager = PluginManager::getInstance();
        /** @var BasePlugin $plugin */
        foreach($pluginManager->getPlugins() as $plugin){
            if(\strtolower($plugin->getSystemName()) !== $request->getSecondUriPart())
                continue;
            $this->render('plugins/plugin', ['route' => $request->getFirstUriPart(), 'plugin' => $plugin]);
        }
    }

    /**
     * Get the view to add an Plugin.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function getAddPlugin(Request $request, Response $response){
        $this->render('plugins/addplugin', ['route' => $request->getFirstUriPart()]);
    }

    /**
     * Add an Plugin to the system.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function addPlugin(Request $request, Response $response){
        $fileType = "application/x-zip-compressed";
        $fileExt  = ".zip";
        $pluginManager = PluginManager::getInstance();
        $errors = [];

        if(count($_FILES) !== 1 && array_key_exists('plugin', $_FILES))
            $response->sendToPage('/plugin/add');

        $file = $_FILES['plugin'];
        $filepath = TMP_FILES . $file['name'];
        $fileUploadErrors = array(
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        if($file['error'] !== 0){
            $errors[] = $fileUploadErrors[$file['error']];
        } else if($file['type'] !== $fileType){
            $errors[] = "The chosen file is not a valid zip file";
        } else if(substr($file['name'], strlen($file['name']) - 4) != $fileExt){
            $errors[] = "Invalid zip file extension";
        } else if(move_uploaded_file($file['tmp_name'], $filepath) === false){
            $errors[] = "Something went wrong while processing your file. please try again";
        }

        if(empty($errors)){
            try{
                $pluginManager->installPlugin($filepath, PLUGINS_PATH);
                $response->sendToPage('/plugins');
            } catch(PluginException $e){
                $errors[] = $e->getMessage();
            }
        }
        $this->render(
            "plugins/addplugin", 
            [
                'route' => $request->getFirstUriPart(), 
                'errors' => FormBuilder::addFormErrors($errors)
            ]
        );
    }

    /**
     * Update the configuration of an Plugin.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function updatePlugin(Request $request, Response $response){
        if(count($_POST) == 0)
            $response->sendToPage('/plugin/' . $_POST['plugin']);

        $plugin = PluginManager::getInstance()->getPlugin($_POST['plugin']);
        $isUpdated = $plugin->updateConfig($_POST);

        if($isUpdated){
            $response->sendToPage('/plugin/' . $_POST['plugin']);
        }
        $this->render(
            'plugins/plugin', 
            [
                'errors' => ['Something went wrong while trying to update the plugin information']
            ]
        );
    }

    /**
     * Delete a Plugin.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function deletePlugin(Request $request, Response $response){
        $pluginSysName = $_GET['plugin'];
        $pluginManager = PluginManager::getInstance();
        $plugin = $pluginManager->getPlugin($pluginSysName);
        
        if($plugin === null) return;
        $pluginManager->uninstallPlugin($pluginSysName);
        $this->render('plugins/plugins', ['plugins' => $pluginManager->getPlugins()]);
    }
}