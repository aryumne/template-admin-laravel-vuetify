<?php

namespace App\Repositories;

use App\Exceptions\CustomExceptionHandler;
use Exception;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;

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
            if (!$data) throw new CustomExceptionHandler("Data obat tidak ditemukan!", 404);
            return $data;
        } catch (CustomExceptionHandler $e) {
            throw $e;
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
            if (isset($data['name'])) {
                if ($record->name !== $data['name']) {
                    $checkData = $this->checkExistingDataWithTrashed('name', $data['name']);
                    if ($checkData) throw new CustomExceptionHandler("Nama obat ini telah terdaftar!", 422);
                    $record->name = $data['name'];
                }
            }
            if (isset($data['batch_number'])) {
                if ($record->batch_number !== $data['batch_number']) {
                    Log::info($record->batch_number . ' === ' . $data['batch_number']);
                    $checkData = $this->checkExistingDataWithTrashed('batch_number', $data['batch_number']);
                    Log::info(json_encode($checkData));
                    if ($checkData) throw new CustomExceptionHandler("Nomor batch ini telah terdaftar!", 422);
                    $record->batch_number = $data['batch_number'];
                }
            }
            isset($data['pack_stok']) && $record->pack_stok = $data['pack_stok'];
            isset($data['items_per_pack']) && $record->items_per_pack = $data['items_per_pack'];
            isset($data['pack_price']) && $record->pack_price = $data['pack_price'];
            isset($data['item_price']) && $record->item_price = $data['item_price'];
            isset($data['total_item']) && $record->total_item = $data['total_item'];
            isset($data['expired_date']) && $record->expired_date = $data['expired_date'];
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

    public function delete(string $uuid)
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

    public function search(string $search, $limit)
    {
        try {
            return $this->model->search($search, null, true, true)->limit($limit)->get();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
