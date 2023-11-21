<?php

namespace App\Repositories;

use Exception;
use App\Models\BlogType;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BlogTypeResource;

class BlogTypeRepository extends BaseRepository
{
    protected $model;

    function __construct(BlogType $loc)
    {
        $this->model = $loc;
    }

    public function getAll()
    {
        $data = $this->model->all();
        return !is_null($data) ? BlogTypeResource::collection($data) : null;
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $data = BlogType::create($data);
            DB::commit();
            return new BlogTypeResource($data);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return null;
    }
}
