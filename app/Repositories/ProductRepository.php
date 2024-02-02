<?php

namespace App\Repositories;

use App\Exceptions\CustomExceptionHandler;
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
    }

    public function getOneByCondition($cond, $relations = [])
    {
        try {
            $data = $this->model->where($cond['key'], $cond['value'])->with($relations)->first();
            return new ProductResource($data);
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
            return new ProductResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data, string $uuid)
    {
        DB::beginTransaction();
        try {
            $record = $this->model->find($uuid);
            if (!$record) throw new CustomExceptionHandler('Data obat tidak ditemukan!', 404);
            isset($data['name']) && $record->name = $data['name'];
            isset($data['batch_number']) && $record->batch_number = $data['batch_number'];
            isset($data['stok_by_pack']) && $record->stok_by_pack = $data['stok_by_pack'];
            isset($data['stok_by_item']) && $record->stok_by_item = $data['stok_by_item'];
            isset($data['pack_price']) && $record->pack_price = $data['pack_price'];
            isset($data['item_price']) && $record->item_price = $data['item_price'];
            isset($data['total_item']) && $record->total_item = $data['total_item'];
            isset($data['product_type_id']) && $record->product_type_id = $data['product_type_id'];
            $record->save();
            DB::commit();
            return new ProductResource($record);
        } catch (CustomExceptionHandler $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($uuid)
    {
        DB::beginTransaction();
        try {
            $record = $this->model->find($uuid);
            if (!$record) throw new CustomExceptionHandler('Data obat tidak ditemukan!', 404);
            $record->delete();
            DB::commit();
            return true;
        } catch (CustomExceptionHandler $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
