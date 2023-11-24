<?php

namespace App\Repositories;

use Exception;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BlogResource;

class BlogRepository extends BaseRepository
{
    protected $model;

    function __construct(Blog $blog)
    {
        $this->model = $blog;
    }

    public function getAll()
    {
        try {
            $data = $this->model->all();
            return !is_null($data) ? BlogResource::collection($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function getById($uuid, $relations = [])
    {
        try {
            $data = $this->model->find($uuid)->with($relations)->first();
            return new BlogResource($data);
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $newData = [
                'title'          => $data['title'],
                'short_desc'     => $data['short_desc'],
                'thumb_url'      => $data['thumb_url'],
                'desc'           => $data['desc'],
                'is_recomended'  => $data['is_recomended'],
                'blog_type_id'   => $data['blog_type_id'],
            ];
            $record = $this->model->create($newData);
            DB::commit();
            return new BlogResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return null;
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
        return null;
    }
}
