<?php

namespace App\Repositories;

use Exception;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;

class ProductRepository extends BaseRepository
{
    protected $model;

    function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function getAll()
    {
        try {
            $data = $this->model->all();
            return !is_null($data) ? ProductResource::collection($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function getOneByCondition($cond, $relations = [])
    {
        try {
            $data = $this->model->where($cond['key'], $cond['value'])->with($relations)->first();
            return new ProductResource($data);
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }


    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $record = $this->model->create($data);
            DB::commit();
            return new ProductResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return null;
    }
}
