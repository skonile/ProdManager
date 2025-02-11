<?php
declare(strict_types = 1);

namespace App\Plugins;

use App\Database;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\FormBuilder\FormBuilder;

/**
 * The base class that every plugin's main class implements.
 */
abstract class BasePlugin{
    /**
     * Name of the plugin.
     */
    protected string $name;

    /**
     * The directory/filename of the plugin.
     */
    protected string $systemName;

    /**
     * Description for the plugin.
     */
    protected string $description = '';

    /**
     * Version number of the plugin.
     */
    protected ?string $version = null;

    /**
     * Author(s) of the plugin
     *
     * @var string|null|array<string>
     */
    protected string|array|null $author;

    /**
     * Client object for the API of the plugin.
     */
    protected mixed $client = null;

    /**
     * @var Database $database Database connection
     */
    protected Database $database;

    public function __construct(string $name, string $systemName, Database $database){
        $this->name       = $name;
        $this->systemName = $systemName;
        $this->database   = $database;
    }

    #region Plugin data methods
    public function getName(): string{
        return $this->name;
    }

    public function getSystemName(): string{
        return $this->systemName;
    }

    public function getDescription(): string{
        return $this->description;
    }

    public function getVersion(): string{
        return $this->version;
    }

    public function getAuthor(): string{
        return $this->author;
    }
    #endregion

    #region Plugin API connections 
    /**
     * Create a client connection for the plugin.
     *
     * @return boolean true if the connection was a success
     *                  or false otherwise.
     */
    public function connect(): bool{
        return true;
    }

    /**
     * Add a new product.
     *
     * @param Product $product The product to add
     * @return boolean Returns true the product was added successfully 
     *                  or false otherwise
     */
    abstract public function addProduct(Product $product): bool;

    /**
     * Update an existing product.
     *
     * @param Product $product The product to update
     * @return boolean Returns true the product was updated successfully 
     *                  or false otherwise
     */
    abstract public function updateProduct(Product $product): bool;

    /**
     * Delete an existing product.
     *
     * @param Product $product The product to delete
     * @return boolean Returns true the product was deleted successfully 
     *                  or false otherwise
     */
    abstract public function deleteProduct(int $productId): bool;

    /**
     * Add a new tag.
     *
     * @param Tag $tag The tag to add
     * @return boolean Returns true the tag was added successfully 
     *                  or false otherwise
     */
    abstract public function addProductTag(Tag $tag): bool;

    /**
     * Update an existing tag.
     *
     * @param Tag $tag The tag to update
     * @return boolean Returns true the tag was updated successfully 
     *                  or false otherwise
     */
    abstract public function updateProductTag(Tag $tag): bool;

    /**
     * Delete an existing tag.
     *
     * @param Tag $tag The tag to delete
     * @return boolean Returns true the tag was deleted successfully 
     *                  or false otherwise
     */
    abstract public function deleteProductTag(Tag $tag): bool;

    /**
     * Add a new category.
     *
     * @param Category $category The category to add
     * @return boolean Returns true the category was added successfully 
     *                  or false otherwise
     */
    abstract public function addProductCategory(Category $category): bool;

    /**
     * Update an existing category.
     *
     * @param Category $category The category to update
     * @return boolean Returns true the category was updated successfully 
     *                  or false otherwise
     */
    abstract public function updateProductCategory(Category $category): bool;

    /**
     * Delete an existing category.
     *
     * @param Category $category The category to delete
     * @return boolean Returns true the category was deleted successfully 
     *                  or false otherwise
     */
    abstract public function deleteProductCategory(Category $category): bool;

    /**
     * Add a new brand.
     *
     * @param Brand $brand The brand to add
     * @return boolean Returns true the brand was added successfully 
     *                  or false otherwise
     */
    abstract public function addProductBrand(Brand $brand): bool;

    /**
     * Update an existing brand.
     *
     * @param Brand $brand The brand to update
     * @return boolean Returns true the brand was updated successfully 
     *                  or false otherwise
     */
    abstract public function updateProductBrand(Brand $brand): bool;

    /**
     * Delete an existing brand.
     *
     * @param Brand $brand The brand to delete
     * @return boolean Returns true the brand was deleted successfully 
     *                  or false otherwise
     */
    abstract public function deleteProductBrand(Brand $brand): bool;
    #endregion

    #region Plugin Install and Unistall methods
    /**
     * Run the installation code and add plugin to installed plugins.
     * 
     * The code can involve creating a table(s) for the plugin to 
     * store configuration and other data for the plugin to function.
     *
     * @return boolean true if everything was created well 
     *                  or false otherwise.
     */
    public function install(): bool{
        return $this->addToInstalledPlugins();
    }

    /**
     * Remove alL the plugin related table(s) and from installed plugins.
     *
     * @return boolean true if everything was removed well 
     *                  or false otherwise.
     */
    public function uninstall(): bool{
        $res = $this->removeFromInstalledPlugins();
        $res2 = $this->removeFromProductPlugins();
        if($res && $res2){
            return true;
        }
        return false;
    }
    #endregion

    #region Plugin Fields handling
    /**
     * Get the HTML form for the settings of the plugin.
     * 
     * This data is about how the form will be like,
     * it is used to create a custom form specifically
     * for the given plugin.
     * 
     * @return ?FormBuilder The HTML form builder or null
     */
    public function getConfigViewFormFeilds(): FormBuilder|string|null{
        return null;
    }

    /**
     * Update the plugins settings.
     * 
     * @param array $args The feilds to update
     */
    public function updateConfig(array $args = []): bool{
        return true;
    }

    /**
     * Get configuration data from the database.
     *
     * @return array The configuration data
     */
    public function getConfig(): array{
        return [];
    }

    /**
     * Get the additional product fields that the plugin requires.
     * 
     * These are fields like different product ids or image links that will link to the
     * local images or some other fields that are not implemented by the product.
     *
     * @return FormBuilder|string|null  Returns null if there are not fields 
     *                                  or the fields can returned as a string 
     *                                  or the formBuilder object.
     */
    public function getPluginFields(?int $prodId = null): FormBuilder|string|null{
        return null;
    }

    /**
     * Add the custom plugin data to the plugin database table.
     *
     * @param integer $prodId The product to add plugin fields of
     * @param array $fields The fields to add
     * @return boolean Returns true if the fields where added successful or false otherwise
     */
    public function addPluginFields(int $prodId, array $fields): bool{
        return true;
    }

    /**
     * Update the custom plugin data of the plugin database table.
     *
     * @param integer $prodId The product to update plugin fields of
     * @param array $fields The fields to update
     * @return boolean Returns true if the fields where updated successful or false otherwise
     */
    public function updatePluginFields(int $prodId, array $fields): bool{
        return true;
    }

    /**
     * Remove the custom plugin data in the plugin database table of the given product.
     *
     * @param integer $prodId The product to remove plugin fields of
     * @return boolean Returns true if the fields where removed successful or false otherwise
     */
    public function removePluginFields(int $prodId): bool{
        return true;
    }
    #endregion

    /**
     * Add the current plugin to installed plugins table.
     * 
     * @return bool Return true if the plugin was added to
     *              plugins database table
     */
    protected function addToInstalledPlugins(): bool{
        $sql = "INSERT INTO plugins (plugin_name, plugin_sys_name) VALUES (?, ?)";
        $res = $this->database->preparedQuery($sql, [$this->getName(), $this->systemName]);
        if($res === false)
            return false;
        return true;
    }

    /**
     * Remove all entries that reference this plugin in the product_plugin table.
     *
     * @return boolean Returns true if all the rows are removed or false otherwise
     */
    protected function removeFromProductPlugins(): bool{
        $sql = "DELETE FROM product_plugin WHERE plugin_sys_name = ?";
        if($this->database->preparedQuery($sql, [$this->getSystemName()])) return true;
        return false;
    }

    /**
     * Remove the current to the installed plugins table.
     * 
     * @return bool Returns true if query was successful or false otherwise
     */
    protected function removeFromInstalledPlugins(): bool{
        $sql = "DELETE FROM plugins WHERE plugin_sys_name = ?";

        if($this->database->preparedQuery($sql, [$this->systemName]) === false)
            return false;
        return true;
    }
}