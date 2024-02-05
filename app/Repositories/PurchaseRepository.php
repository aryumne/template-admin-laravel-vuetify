<?php

namespace App\Repositories;

use Exception;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PurchaseResource;
use App\Exceptions\CustomExceptionHandler;

class PurchaseRepository extends BaseRepository
{
    protected $model, $productModel;

    function __construct(Purchase $purchase, Product $product)
    {
        $this->model = $purchase;
        $this->productModel = $product;
    }

    public function getAll()
    {
        try {
            $data = $this->model->all();
            return !is_null($data) ? PurchaseResource::collection($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOneByCondition($cond, $relations = [])
    {
        try {
            $data = $this->model->where($cond['key'], $cond['value'])->with($relations)->first();
            return new PurchaseResource($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            if (!isset($data['stocks']) || count($data['stocks']) === 0) throw new CustomExceptionHandler('Daftar pembelian obat tidak ditemukan!');
            foreach ($data['stocks'] as $stok) {
                $purchase = $this->model->create([
                    'name' => $stok['name'],
                    'product_id' => $stok['product_id'],
                    'quantity' => $stok['quantity'],
                    'price' => $stok['price'],
                    'total_price' => $stok['total_price'],
                ]);

                if (!$purchase) throw new CustomExceptionHandler("Gagal menyimpan data pembelian obat " . $stok['name'] . "!");
                $updateProduct =  $this->increaseProduct($purchase->product_id, $purchase->quantity);
                if ($updateProduct != true) throw new CustomExceptionHandler("Gagal menyimpan perubahan data obat " . $stok['name'] . "!");
            }
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

    private function increaseProduct($productId, $quantity)
    {
        try {
            $product = $this->productModel->where('id', $productId)->first();
            if (!$product) throw new CustomExceptionHandler("Data obat tidak tersedia!", 404);
            $product->pack_stok = $product->pack_stok + $quantity;
            $product->total_item = $product->total_item + ($quantity * $product->items_per_pack);
            $product->save();
            return true;
        } catch (CustomExceptionHandler $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
