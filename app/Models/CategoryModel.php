<?php
declare(strict_types = 1);

namespace App\Models;

use App\Database;

class CategoryModel extends BaseModel{
    public function __construct(Database $db){
        parent::__construct($db);
    }

    /**
     * Get all the categories in the database.
     * 
     * @return array An array containing category objects 
     *               of all the categories in the database
     */
    public function getAllCategories(): array{
        $sql = "SELECT * FROM category";
        $categories = [];

        $result = $this->database->preparedQuery($sql);
        while($row = $result->fetch_assoc())
            $categories[] = $this->convertDBCategoryToCategoryObj($row);

        return $categories;
    }

    /**
     * Get the categories of a given product using it's id.
     * 
     * @param int $prodId The product id
     * @return array The array of categories
     */
    public function getCategoriesByProductId(int $prodId): array{
        $sql = "SELECT * FROM product_category 
                WHERE prod_id = ?";
        $catIds = [];
        $categories = [];

        $res = $this->database->preparedQuery($sql, [$prodId]);
        for($ctr = 0; $ctr < $res->num_rows; $ctr++){
            $row = $res->fetch_assoc();
            $catIds[] = $row['cat_id'];
        }

        if(\count($catIds) != 0){
             $sql = "SELECT * FROM category WHERE id 
                    IN (" . \implode(", ", $catIds) . ")";
            $res = $this->database->query($sql);
            for($ctr = 0; $ctr < $res->num_rows; $ctr++){
                $row = $res->fetch_assoc();
                $categories[] = $this->convertDBCategoryToCategoryObj($row);
            }
        }

        return $categories;
    }

    /**
     * Get a single category in the database using its id.
     * 
     * @return Category|bool A category with the matching id or false otherwise
     */
    public function getCategory(int $catId): Category|bool{
        $sql = "SELECT * FROM category WHERE id = ?";
        $res = $this->database->preparedQuery($sql, [$catId]);
        if($res != false && $res->num_rows != 0)
            return $this->convertDBCategoryToCategoryObj($res->fetch_assoc());
        return false;
    }

    /**
     * Add a new category to the database.
     * 
     * @param string $name
     * @param string $slug
     * @param ?int $parent
     * @param bool $isPublished
     * @return Category|bool The new added category with its insert id 
     *                       or false if the category was not added
     */
    public function addCategory(string $name, string $slug, ?int $parent, bool $isPublished = false): Category|bool{
        $sql = "INSERT INTO category (name, slug, parent_id, is_published) VALUES (?, ?, ?, ?)";
        $params = [$name, $slug, $parent, $isPublished];
        $res = $this->database->preparedQueryWithBool($sql, $params);

        if(!$res) return false;
        $catId = $this->database->getInsertId();
        
        return new Category($catId, $name, $slug, $parent, $isPublished);
    }

    /**
     * Updates an already existing category.
     * 
     * @param int $id The id of the category to update
     * @param string $name
     * @param string $slug
     * @param ?int $parent
     * @param bool $isPublished
     * @return bool Returns true if category was updated successfully 
     *              or false otherwise.
     */
    public function updateCategory(int $id, string $name, string $slug, ?int $parent, bool $isPublished): bool{  
        $sql = "UPDATE category SET 
                name = ?, slug = ?, parent_id = ?, 
                is_published = ? WHERE id = ?";
        $params = [$name, $slug, $parent, $isPublished, $id];
        return $this->database->preparedQueryWithBool($sql, $params);
    }

    /**
     * Delete category from categories.
     * 
     * @param $id The category id
     * @return bool Returns true if the category was successfully deleted
     */
    public function deleteCategory(int $id): bool{
        $categoryParentId = $this->getCategory($id)->getParentId();
        $categoryChildren = $this->getChildrenCategories($id);

        for($ctr = 0; $ctr < count($categoryChildren); $ctr++){
            $child = $categoryChildren[$ctr];
            $child->setParentId($categoryParentId);
            $this->updateCategory(
                $child->getId(), 
                $child->getName(), 
                $child->getSlug(), 
                $child->getParentId(), 
                $child->getIsPublished()
            );
        }

        $sql = "DELETE FROM category WHERE id = ?";
        return $this->database->preparedQuery($sql, [$id]);
    }

    /**
     * Get the categories that have the given category id as their parent
     * 
     * @param int $categoryId The id of the category to get children of
     * @return array An array of all the children
     */
    private function getChildrenCategories(int $categoryId): array{
        $childrenCategories = [];
        $sql = "SELECT * FROM category WHERE parent_id = ?";
        $res = $this->database->preparedQuery($sql, [$categoryId]);
        
        if($res->num_rows == 0) return [];

        while($row = $res->fetch_assoc())
            $childrenCategories[] = $this->convertDBCategoryToCategoryObj($row);
        return $childrenCategories;
    }

    protected function convertDBCategoryToCategoryObj(array $dbCat): Category{
        return new Category(
            id: (int) $dbCat['id'],
            name: $dbCat['name'],
            slug: $dbCat['slug'],
            parent: ($dbCat['parent_id'] == null)? null: (int) $dbCat['parent_id'],
            isPublished: ($dbCat['is_published'] == 0)? false: true
        );
    }
}