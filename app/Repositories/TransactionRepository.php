<?php

namespace App\Repositories;

use App\Enums\OrderTypeEnum;
use Exception;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
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

    public function update(array $data, string $uuid)
    {
        if (!isset($data['orders']) || count($data['orders']) === 0) throw new CustomExceptionHandler('Daftar pembelian obat tidak ditemukan!');
        DB::beginTransaction();
        try {
            $record = $this->model->find($uuid);

            if (!$record) throw new CustomExceptionHandler('Data transaksi tidak ditemukan!', 404);
            isset($data['amount']) && $record->amount = $data['amount'];
            isset($data['cash_amount']) && $record->cash_amount = $data['cash_amount'];
            isset($data['return_amount']) && $record->return_amount = $data['return_amount'];
            isset($data['prescription_number']) && $record->prescription_number = $data['prescription_number'];
            $record->save();
            $this->syncOrderOnReturn($record->transaction_number, $record->sales->toArray(), $data['orders']);
            DB::commit();
            return new TransactionResource($record);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function arrayObjectCompare($item, $array)
    {
        return !in_array($item['barcode'], array_column($array, 'barcode'));
    }

    private function syncOrderOnReturn($transactionNumber, $storedOrders, $currentOrders)
    {
        // dapatkan orderan lama (yang sudah tersimpan) yang tidak ada di orderan baru
        $returnOrders = array_filter($storedOrders, function ($item) use ($currentOrders) {
            return $this->arrayObjectCompare($item, $currentOrders);
        });

        if (count($returnOrders) > 0) {
            foreach ($returnOrders as $order) {
                // hapus semua orderan lama
                $deleteOrder = $this->salesModel->where('id', $order['id'])->delete();
                //kembalikan stoknya
                if ($deleteOrder != 0) $this->increaseProduct($order['barcode'], $order['type'], $order['quantity']);
            }
        }

        // dapatkan orderan baru yang tidak ada di orderan lama
        $newOrders = array_filter($currentOrders, function ($item) use ($storedOrders) {
            return $this->arrayObjectCompare($item, $storedOrders);
        });

        if (count($newOrders) > 0) {
            foreach ($newOrders as $order) {
                $sales = $this->salesModel->create([
                    'transaction_number' => $transactionNumber,
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
        }
    }

    private function decreaseProduct(string $barcode, string $type, int $quantity)
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

    private function increaseProduct(string $barcode, string $type, int $quantity)
    {
        try {
            $product = $this->productModel->where('barcode', $barcode)->first();
            if (!$product) throw new CustomExceptionHandler("Data obat $barcode tidak tersedia!");
            if ($type === OrderTypeEnum::PACK) {
                $product->pack_stok = $product->pack_stok + $quantity;
                $product->total_item = $product->total_item + ($quantity * $product->items_per_pack);
            } else if ($type === OrderTypeEnum::PCS) {
                $product->total_item = $product->total_item + $quantity;
                $product->pack_stok = floor($product->total_item / $product->items_per_pack);
            } else throw new CustomExceptionHandler("Type pembelian obat " . $product->name . "tidak valid!");
            $product->save();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
