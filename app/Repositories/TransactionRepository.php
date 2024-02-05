<?php

namespace App\Repositories;

use App\Enums\OrderTypeEnum;
use Exception;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Sales;

class TransactionRepository extends BaseRepository
{
    protected $model, $salesModel, $productModel;

    function __construct(Transaction $loc, Sales $sales, Product $product)
    {
        $this->model = $loc;
        $this->salesModel = $sales;
        $this->productModel = $product;
    }

    public function getAll()
    {
        try {
            $data = $this->model->all();
            return !is_null($data) ? TransactionResource::collection($data) : null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOneByCondition($cond, $relations = [])
    {
        try {
            $data = $this->model->where($cond['key'], $cond['value'])->with($relations)->first();
            return new TransactionResource($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            if (!isset($data['orders']) || count($data['orders']) === 0) throw new CustomExceptionHandler('Daftar pembelian obat tidak ditemukan!');
            $record = $this->model->create([
                'amount'              => $data['amount'],
                'cash_amount'         => $data['cash_amount'],
                'return_amount'       => $data['return_amount'],
                'prescription_number' => $data['prescription_number'],
            ]);
            Log::info($record);
            foreach ($data['orders'] as $order) {
                $sales = $this->salesModel->create([
                    'transaction_number' => $record->transaction_number,
                    'name'        => $order['name'],
                    'barcode'     => $order['barcode'],
                    'type'        => $order['type'],
                    'quantity'    => $order['quantity'],
                    'price'       => $order['price'],
                    'total_price' => $order['total_price'],
                ]);
                if (!$sales) throw new CustomExceptionHandler("Gagal menyimpan data pembelian obat " . $order['name'] . "!");
                $updateProduct =  $this->decreaseProduct($sales->barcode, $sales->type, $sales->quantity);
                if ($updateProduct != true) throw new CustomExceptionHandler("Gagal menyimpan perubahan data obat " . $order['name'] . "!");
            }
            DB::commit();
            return new TransactionResource($record);
        } catch (CustomExceptionHandler $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function decreaseProduct($barcode, $type, $quantity)
    {
        try {
            $product = $this->productModel->where('barcode', $barcode)->first();
            if (!$product) throw new CustomExceptionHandler("Data obat $barcode tidak tersedia!");
            if ($type === OrderTypeEnum::PACK) {
                if ($product->pack_stok < $quantity) throw new CustomExceptionHandler($product->name . "ini melebihi jumlah stok yang tersedia");
                $product->pack_stok = $product->pack_stok - $quantity;
                $product->total_item = $product->total_item - ($quantity * $product->items_per_pack);
            } else if ($type === OrderTypeEnum::PCS) {
                if ($product->total_item < $quantity) throw new CustomExceptionHandler($product->name . " ini melebihi jumlah stok yang tersedia");
                $product->total_item = $product->total_item - $quantity;
                $product->pack_stok = floor($product->total_item / $product->items_per_pack);
            } else throw new CustomExceptionHandler("Type pembelian obat " . $product->name . "tidak valid!");
            $product->save();
            return true;
        } catch (CustomExceptionHandler $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
