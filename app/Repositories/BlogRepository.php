<?php

namespace App\Repositories;

use Exception;
use App\Models\Blog;
use App\Models\BlogType;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BlogResource;
use App\Http\Resources\frontend\FeBlogResource;

class BlogRepository extends BaseRepository
{
    protected $model, $btModel;

    function __construct(Blog $blog, BlogType $blogType)
    {
        $this->model = $blog;
        $this->btModel = $blogType;
    }

    public function getAll()
    {
        try {
            $data = $this->model->all();
            return !is_null($data) ? BlogResource::collection($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllWithGrouping()
    {
        try {
            $data = $this->model->with('blogType')->selectRaw('count(*) as total_blog, blog_type_id')->groupBy('blog_type_id')->get();
            return !is_null($data) ? $data : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOneByCondition($cond, $relations = [])
    {
        try {
            $data = $this->model->where($cond['key'], $cond['value'])->with($relations)->first();
            return !is_null($data) ? new BlogResource($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOneBySlug($cond, $relations = [])
    {
        try {
            $data = $this->model->where($cond['key'], $cond['value'])->with($relations)->first();
            return !is_null($data) ? new FeBlogResource($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getBlogsByTypeKey($key, $isRecomended = false)
    {
        try {
            $query = $this->model->query();
            $query = $query->where('blog_type_key', $key);
            if ($isRecomended) $query = $query->where('is_recomended', true);
            $data = $query->with(['blogType'])->latest()->get();
            return !is_null($data) ? FeBlogResource::collection($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $blogType = $this->btModel->find($data['blog_type_id']);
            if (!$blogType) throw new Exception('Blog type not found!');
            $newData = [
                'title'          => $data['title'],
                'short_desc'     => $data['short_desc'],
                'thumb_url'      => $data['thumb_url'],
                'desc'           => $data['desc'],
                'is_recomended'  => $data['is_recomended'],
                'blog_type_id'   => $blogType->id,
                'blog_type_key'  => $blogType->key,
            ];
            $record = $this->model->create($newData);
            DB::commit();
            return new BlogResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data, $uuid)
    {
        DB::beginTransaction();
        try {
            $record = $this->model->find($uuid);
            if (!$record) throw new Exception('The blog is not found!');

            isset($data['title']) && $record->title = $data['title'];
            isset($data['short_desc']) && $record->short_desc = $data['short_desc'];
            isset($data['thumb_url']) && $record->thumb_url = $data['thumb_url'];
            isset($data['desc']) && $record->desc = $data['desc'];
            isset($data['is_recomended']) && $record->is_recomended = $data['is_recomended'];
            if (isset($data['blog_type_id'])) {
                $blogType = $this->btModel->find($data['blog_type_id']);
                if (!$blogType) throw new Exception('Blog type not found!');
                $record->blog_type_id = $blogType->id;
                $record->blog_type_key = $blogType->key;
            }
            $record->save();
            DB::commit();
            return new BlogResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($uuid)
    {
        DB::beginTransaction();
        try {
            $this->model->find($uuid)->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
