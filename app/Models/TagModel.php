<?php
declare(strict_types = 1);

namespace App\Models;

use App\Database;

class TagModel extends BaseModel{
    public function __construct(Database $db){
        parent::__construct($db);
    }

    /**
     * Get all the tags in the database.
     * 
     * @return array<Tag> An array of Tag objects 
     *              of all the tags in the database
     */
    public function getAllTags(): array{
        $sql = "SELECT * FROM tag";
        $tags = [];
        $result = $this->database->query($sql);
        while($row = $result->fetch_assoc()){
            $tags[] = $this->convertDBTagToTagObj($row);
        }
        return $tags;
    }

    /**
     * Get the tags of a given product using it's id.
     * 
     * @param int $prodId The product id
     * @return array<Tag> The array of tags
     */
    public function getTagsByProductId(int $prodId): array{
        $sql = "SELECT * FROM product_tag 
                WHERE prod_id = ?";
        $tagIds = [];
        $tags = [];

        $res = $this->database->preparedQuery($sql, [$prodId]);
        for($ctr = 0; $ctr < $res->num_rows; $ctr++){
            $row = $res->fetch_assoc();
            $tagIds[] = $row['tag_id'];
        }

        if(\count($tagIds) != 0){
            $sql = "SELECT * FROM tag WHERE id 
                    IN (" . \implode(", ", $tagIds) . ")";
            $res = $this->database->query($sql);
            for($ctr = 0; $ctr < $res->num_rows; $ctr++){
                $row = $res->fetch_assoc();
                $tag = $this->convertDBTagToTagObj($row);
                $tags[] = $tag;
            }
        }
        
        return $tags;
    }

    /**
     * Get a tag object using its id.
     * 
     * @param int $id The tag id
     * @return Tag|bool Returns the tag object or 
     *                  false if the tag does not exist
     */
    public function getTag(int $id): Tag|bool{
        if($id < 1) return false;
        $sql = "SELECT * FROM tag WHERE id = ?";
        $result = $this->database->preparedQuery($sql, [$id]);
        
        if($result->num_rows != 1) return false;
        return $this->convertDBTagToTagObj($result->fetch_assoc());
    }

    /**
     * Adds a new tag to the database.
     * 
     * @param string $name
     * @param string $slug 
     * @return Tag|bool Returns the newly added tag 
     *                  or false if no tag was added
     */
    public function addTag(string $tagName, string $tagSlug, bool $isPublished): Tag|bool{
        $sql = "INSERT INTO tag (name, slug, is_published) VALUES (?, ?, ?)";
        $res = $this->database->preparedQuery($sql, [$tagName, $tagSlug, $isPublished]);

        if(!$res) return false;
        $tagId = $this->database->getInsertId();
        return new Tag($tagId, $tagName, $tagSlug, $isPublished);
    }

    /**
     * Updates an existing tag in the database.
     * 
     * @param int $id The tag id to update
     * @param string $tagName The tag name
     * @param string $tagSlug The tag slug
     * @param bool $isPublished The status of the tag
     * @return Tag|bool Returns the updated if it was 
     *                  successfully updated or false otherwise
     */
    public function updateTag(int $id, string $name, string $slug, bool $isPublished): Tag|bool{
        $sql = "UPDATE tag SET name = ?, slug = ?, is_published = ? WHERE id = ?";
        if(!$this->database->preparedQuery($sql, [$name, $slug, $isPublished, $id])) return false;
        return new Tag($id, $name, $slug, $isPublished);
    }

    /**
     * Deletes a tag in the database.
     * 
     * @param int $id The id of the tag to delete
     * @return bool Returns true when successfully deleted or false otherwise
     */
    public function deleteTag(int $id): bool{
        $sql = "DELETE FROM tag WHERE id = ?";
        return $this->database->preparedQuery($sql, [$id]);
    }

    protected function convertDBTagToTagObj(array $dbTag): Tag{
        return  new Tag(
            id: (int) $dbTag['id'],
            name: $dbTag['name'],
            slug: $dbTag['slug'],
            isPublished: ($dbTag['is_published'] == 0)? false: true
        );
    }
}