<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Request;
use App\Response;
use App\Models\Tag;
use App\Models\TagModel;
use App\Database;

class TagsController extends BaseController{
    private TagModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new TagModel(Database::getInstance());
    }

    public function getTags(Request $request, Response $response){
        $tags = $this->model->getAllTags();

        $this->render(
            'tags/tags', 
            [
                'route' => $request->getFirstUriPart(),
                'tags' => $tags
            ]
        );
    }

    public function getTag(Request $request, Response $response){
        $tagId = $this->getPageOrLimitNum($request->getSecondUriPart());
        if(!$tagId) $response->sendToPage('/tags');
        $tag = $this->model->getTag($tagId);

        $this->renderTag($request->getUri(), $tag);
    }

    public function getCreateTag(Request $request, Response $response){
        $this->renderTag($request->getUri());
    }

    public function createTag(Request $request, Response $response){
        $tagName = $_POST['tag-name'];
        $tagSlug = $_POST['tag-slug'];
        $isTagPublished = (bool) $_POST['tag-status'];
        $tag = $this->model->addTag($tagName, $tagSlug, $isTagPublished);
        
        $response->sendToPage('/tag/' . $tag->getId());
    }

    public function updateTag(Request $request, Response $response){
        $tagId = (int) $_POST['tag-id'];
        $tagName = $_POST['tag-name'];
        $tagSlug = $_POST['tag-slug'];
        $tagIsPublished = (bool) $_POST['tag-status'];
        $oldTag = $this->model->getTag($tagId);
        $updatedTag = $this->model->updateTag($tagId, $tagName, $tagSlug, $tagIsPublished);
        $tag = $updatedTag;
        $msg = '';
        
        if($updatedTag === false){
            $tag = $oldTag;
            $msg = "Something went wrong while updating tag. Please try again.";
        } else {
            $msg = "Tag updated successfully.";
        }

        $response->sendToPage('/tag/' . $tag->getId());
    }

    public function deleteTag(Request $request, Response $response){
        $tagId = $_GET['tag-id'];
        $this->model->deleteTag( (int) $tagId);
        $response->sendToPage('/tags');
    }

    protected function renderTag(string $route, ?Tag $tag = null, ?string $message = null){
        $this->render(
            "tags/tag",
            [
                'route' => $route,
                'tag' => $tag,
                'message' => $message
            ]
        );
    }
}