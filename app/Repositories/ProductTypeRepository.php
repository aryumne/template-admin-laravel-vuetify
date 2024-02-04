<?php

namespace App\Repositories;

use Exception;
use App\Models\ProductType;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductTypeResource;

class ProductTypeRepository extends BaseRepository
{
    protected $model;

    function __construct(ProductType $loc)
    {
        $this->model = $loc;
    }

    public function getAll()
    {
        try {
            $data = $this->model->all();
            return !is_null($data) ? ProductTypeResource::collection($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOneByCondition($cond, $relations = [])
    {
        try {
            $data = $this->model->where($cond['key'], $cond['value'])->with($relations)->first();
            return new ProductTypeResource($data);
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $record = $this->model->create($data);
            DB::commit();
            return new ProductTypeResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
