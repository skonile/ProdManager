<?php
declare(strict_types = 1);

namespace App\Plugins;

use App\Utils;
use Exception;
use \ZipArchive;

use App\Database;
use App\Plugins\BasePlugin;
use App\Models\ProductPlugin;
use App\Exceptions\PluginException;

class PluginManager{

    /** @property null|array<BasePlugin> $plugins */
    private ?array $plugins = null;

    private string $pluginsPath = PLUGINS_PATH;

    /** @var ?PluginManager $pluginsObj Singleton object */
    static private ?PluginManager $pluginsObj = null;

    private function __construct(){}

    static public function getInstance(){
        if(PluginManager::$pluginsObj == null)
            PluginManager::$pluginsObj = new PluginManager();
        return PluginManager::$pluginsObj;
    }

    /**
     * Get the set path for where plugins are.
     * 
     * @return string The path
     */
    public function getPluginsPath(): string{
        return $this->pluginsPath;
    }

    /**
     * Set a custom path of the directory where plugins are.
     * 
     * @param string $path
     * @return void
     */
    public function setPluginsPath(string $path){
        $this->pluginsPath = $path;
    }

    /**
     * Get the currently loaded plugins.
     *
     * @return array
     */
    public function getPlugins(): array{
        return $this->plugins;
    }

    /**
     * Get loaded plugins.
     * 
     * The method loads the plugins first the return the loaded plugins.
     *
     * @return null|array<BasePlugin>
     */
    public function getLoadedPlugins(): ?array{
        $this->loadPlugins();
        return $this->plugins;
    }

    /**
     * Load plugins from their set directory.
     *
     * @return static
     */
    public function loadPlugins(): static{
        $this->plugins = [];
        $pluginsDirs = $this->getPluginsDirs($this->pluginsPath);
        foreach($pluginsDirs as $pluginDir){
            $res = $this->getPluginUsingPath($pluginDir);
            if($res !== null){
                $pluginDirName = basename($pluginDir);
                $this->plugins[Utils::firstLetterToLower($pluginDirName)] = $res;
            }
        }

        return $this;
    }


    /**
     * Create an object of the plugin using its directory path.
     *
     * @param string $path Path of the plugin directory
     * @return BasePlugin|null Returns the plugin if the object was 
     *                         created successfully or null otherwise.
     * 
     */
    protected function getPluginUsingPath(string $path): ?BasePlugin{
        // Check if a file of the same name as the directory it in exists.
        $pluginDirName = basename($path);
        $filename = $path . DIR_SEP . $pluginDirName . '.php';
        if((!file_exists($filename)) || is_dir($filename)) return null;

        try{
            $class = "Plugins\\$pluginDirName\\$pluginDirName";
            if($this->pluginClassExists($pluginDirName)){
                $obj = new $class(Database::getInstance());
                if($obj instanceof BasePlugin) return $obj;
            }
            return null;
        } catch(Exception $e){
            return null;
        }
    }

    /**
     * Get the plugin object if a plugin with the given name was loaded.
     *
     * @param string $pluginSysName
     * @return BasePlugin|null Return plugin object or false if the plugin was not loaded
     */
    public function getPlugin(string $pluginSysName): ?BasePlugin{
        return $this->{$pluginSysName};
    }

    /**
     * Get all directories in the plugins directory.
     * 
     * @param $pluginsDirPath The path to parent plugins directory
     * @return string[] The list of directories
     */
    private function getPluginsDirs(string $pluginsDirPath): array{
        if(!(\file_exists($pluginsDirPath) && \is_dir($pluginsDirPath))) return [];

        $path = Utils::addDirSepToPath($pluginsDirPath);
        $filesDirs = scandir($path);
        return $this->filterPluginsDirsFromFiles($filesDirs, $pluginsDirPath);
    }

    /**
     * Get the valid plugins directories only.
     *
     * @param array<string> $filesDirs
     * @param string $pluginsDirPath
     * @return array<string> The full path of plugins directories
     */
    private function filterPluginsDirsFromFiles(array $filesDirs, $pluginsDirPath): array{
        $pluginDirs = [];
        foreach($filesDirs as $fileDir){
            if(!is_dir($pluginsDirPath . DIR_SEP . $fileDir)) continue;
            if($fileDir == '.' || $fileDir == '..') continue;
            $pluginDirs[] = $pluginsDirPath . DIR_SEP . $fileDir;
        }
        return $pluginDirs;
    }

    /**
     * Install a given plugin to the system.
     * 
     * Copy the given zip file to a temporary directory and try to extract it 
     * to the plugins directory. Before extracting check if the archive contents
     * are organised well and if the extraction was a success check for the 
     * plugin entry file and if it exists check if it contains the entry class
     *  and if that exists too create the plugin object and and call the install
     * method in it.
     *
     * @param string $zipPluginFilepath The path to the plugin archive
     * @param string $pluginsDir The directory where plugins reside
     * @return void
     */
    public function installPlugin(string $zipPluginFilepath, string $pluginsDir){
        $pluginName = $this->getPluginName($zipPluginFilepath);
        $pluginDir = $pluginsDir . DIR_SEP . $pluginName;

        try{            
            $this->ifContentStructureValidExtract($zipPluginFilepath, $pluginName, $pluginDir);

            $pluginEntryFilepath = $pluginDir . DIR_SEP . $pluginName . '.php';
            if(!file_exists($pluginEntryFilepath))
                throw new PluginException("Could not find the plugin entry file");

            $class = "Plugins\\{$pluginName}\\{$pluginName}";
            if(!$this->pluginClassExists($pluginName))
                throw new PluginException("Could not find plugin entry class");

            $pluginObj = new $class();
            if(!$pluginObj instanceof BasePlugin)
                throw new PluginException("Entry plugin class does not extend the base plugin class");

            if(!$pluginObj->install())
                throw new PluginException("Something went wrong while trying to install the plugin");
        } catch(PluginException $e){
            // Check if the archive was extracted and delete the directory
            // where the archive was newly extracted to.
            if(file_exists($pluginDir)){
                $this->removePluginDir($pluginName);
            }

            throw $e;
        } finally {
            // Delete the uploaded archive file.
            unlink($zipPluginFilepath);
        }
    }

    /**
     * Open the zip file in the memory.
     * 
     * @param string $zipPluginFilepath The path to the plugin zip file
     * @return ZipArchive The open file object
     * @throws PluginException When the is an error when trying to open the zip file
     */
    private function openZipFile(string $zipPluginFilepath): ZipArchive{
        $zipHandler = new ZipArchive();
        $res = $zipHandler->open($zipPluginFilepath);

        if(!($res === true))
            throw new PluginException("Could not open plugin zip file '{$zipPluginFilepath}'");
        return $zipHandler;
    }

    /**
     * Check the structure of the zip file then extract the content.
     * 
     * The structure of the zip file should be either the files are contained
     * in a directory with the same name as the archive name or the files
     * are all not in a directory with the same name as archive.
     * 
     * You can not have files in the archive directory and others have others that are not.
     * 
     * @param string $zipPluginFilepath The path to the archive
     * @param string $pluginName The name of the Archive that is used as the pugin name
     * @param string $pluginDir The plugin path where the plugin will be installed
     * @return void
     * @throws PluginException When the structuree of the archive is bad and 
     *                          the is an error in etracting the files
     */
    private function ifContentStructureValidExtract(
            string $zipPluginFilepath,
            string $pluginName, 
            string $pluginDir): void{
        $zipHandler = $this->openZipFile($zipPluginFilepath);
        $numFiles = $zipHandler->count();
        $filesInFolder = [];
        $filesNotInFolder = [];
        for($ctr = 0; $ctr < $numFiles; $ctr++){
            $filename = $zipHandler->statIndex($ctr)['name'];
            if(strpos($filename, $pluginName . '/') === 0){
                $filesInFolder[] = $filename;
            } else {
                $filesNotInFolder[] = $filename;
            }
        }

        if(!($numFiles == count($filesInFolder) || $numFiles == count($filesNotInFolder))){
            throw new PluginException("Bad directory structure");
        }
        
        $res = false;
        if($numFiles == count($filesNotInFolder)){
            mkdir($pluginDir);
            $res = $zipHandler->extractTo($pluginDir);
        } else {
            $res = $zipHandler->extractTo(dirname($pluginDir));
        }
        
        if($res !== true){
            throw new PluginException("Could not extract plugin files from zip file '{$zipPluginFilepath}'");
        }

        $zipHandler->close();
    }

    /**
     * Removes the plugin files and database entries.
     * 
     * @param $pluginName The directory/plugin name
     * @return bool True if removed successfully or false otherwise
     */
    public function uninstallPlugin(string $pluginName): bool{
        $plugin = $this->getPlugin($pluginName);
        if($plugin === null){
            throw new PluginException("Could not get the plugin to uninstall");
        }

        if(!$plugin->uninstall()){
            throw new PluginException("Could not uninstall the plugin properly");
        }

        $this->removeLoadedPlugin($pluginName);
        $this->removePluginDir($pluginName);

        $productPluginModel = new ProductPlugin(Database::getInstance());
        $productPluginModel->removeRowWithPluginName($pluginName);

        return true;
    }

    /**
     * Remove the plugin directory.
     *
     * @param string $pluginName The plugin system name
     */
    private function removePluginDir(string $pluginName){
        $dirToRemove = $this->pluginsPath . DIR_SEP . $pluginName;
        Utils::deleteDirectory($dirToRemove);
    }

    /**
     * Remove the given plugin to the memory loaded plugins.
     * 
     * Remove the plugin object from the plugins array.
     *
     * @param string $pluginName
     */
    private function removeLoadedPlugin(string $pluginName){
        unset($this->plugins[Utils::firstLetterToLower($pluginName)]);
    }

    /**
     * Get the filename from the zip file filepath.
     *
     * @param string $filepath The zip file filepath
     * @return string The zip file name without the '.zip' extension
     */
    private function getPluginName(string $filepath): string{
        $tempArr = explode(DIR_SEP, $filepath);
        $pluginNameWithExt = $tempArr[count($tempArr) - 1];
        return explode('.', $pluginNameWithExt)[0];
    }

    /**
     * Check if plugin class exists using only its system name.
     *
     * @param string $pluginName The plugin system name
     * @return boolean Return true if it exists or false otherwise
     */
    private function pluginClassExists(string $pluginName): bool{
        return class_exists("Plugins\\{$pluginName}\\{$pluginName}");
    }

    /**
     * Get the plugin object in the loaded plugins using the given name.
     * 
     * This is the same as getPlugin method.
     *
     * @param string $name
     * @return BasePlugin|null Returns the plugin object or null if the plugin was not loaded
     */
    public function __get(string $name): ?BasePlugin{
        $key = Utils::firstLetterToLower($name);
        if(!key_exists($key, $this->plugins)) 
            return null;
        return $this->plugins[$key];
    }
}