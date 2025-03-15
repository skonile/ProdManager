<?php
declare(strict_types = 1);

namespace App\Api\V1\Controllers;

use App\Database;
use App\Models\Tag;
use App\Request;
use App\Response;
use App\Models\TagModel;

class ApiTagController extends ApiBaseController{
    protected TagModel $model;

    public function __construct(){
        parent::__construct();
        $this->model = new TagModel(Database::getInstance());
    }

    public function getTags(Request $request, Response $response){
        $tags = $this->model->getAllTags();
        $response->json(['tags' => $this->tagsToArray($tags)]);
        $response->send();
    }

    public function addTag(Request $request, Response $response){
        try{
            $tagFromRequest = $this->getTagFromRequest($request);
        } catch(\Exception $e){
            $response->json(['error' => $e->getMessage()], 400);
            $response->send();
            exit;
        }

        $tag = $this->model->addTag($tagFromRequest->name, $tagFromRequest->slug, $tagFromRequest->is_published);
        if($tag)
            $response->json(['tag' => $this->tagToArray($tag)], 201);
        else
            $response->json(['error' => 'Tag not added'], 500);
        $response->send();
    }

    public function getTag(Request $request, Response $response){
        $tag = $this->verifyTagIdAndGetTag($request, $response);
        $response->json(['result' => $this->tagToArray($tag)]);
        $response->send();
    }

    public function updateTag(Request $request, Response $response){
        $tag = $this->verifyTagIdAndGetTag($request, $response);
        try{
            $tagFromRequest = $this->getTagFromRequest($request);
        } catch(\Exception $e){
            $response->json(['error' => $e->getMessage()], 400);
            $response->send();
            exit;
        }

        $result = $this->model->updateTag(
            $tag->getId(), 
            $tagFromRequest->name, 
            $tagFromRequest->slug, 
            $tagFromRequest->is_published
        );
        if($result)
            $response->json(['result' => $this->tagToArray($result)]);
        else
            $response->json(['error' => 'Could not update tag'], 500);
        $response->send();
    }

    public function deleteTag(Request $request, Response $response){
        $tag = $this->verifyTagIdAndGetTag($request, $response);
        if(!$this->model->deleteTag($tag->getId()))
            $response->json(['result' => 'success'], 204);
        else
            $response->json(['error' => 'Could not delete tag'], 500);
        $response->send();
    }

    protected function getTagFromRequest(Request $request){
        $tag = json_decode($request->getBody());
        if(isset($tag->name) && isset($tag->slug) && isset($tag->is_published))
            return $tag;
        else
            throw new \Exception('Invalid tag data');
        return $tag;
    }

    protected function verifyTagIdAndGetTag($request, $response): Tag{
        $tagId = $this->getTagId($request);
        if($tagId == 0){
            $response->json(['error' => 'Invalid tag id'], 400);
            $response->send();
            exit;
        }

        $tag = $this->model->getTag($tagId);
        if(!$tag){
            $response->json(['error' => 'Tag not found'], 404);
            $response->send();
            exit;
        }

        return $tag;
    }

    protected function getTagId(Request $request): int{
        return (int) $request->getUriVariable('id');
    }

    protected function tagsToArray($tags){
        return array_map([$this, 'tagToArray'], $tags);
    }

    protected function tagToArray($tag){
        return [
            'id' => $tag->getId(),
            'name' => $tag->getName(),
            'slug' => $tag->getSlug(),
            'is_published' => $tag->getIsPublished()
        ];
    }
}