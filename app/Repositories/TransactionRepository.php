<?php

namespace App\Repositories;

use Exception;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Resources\TransactionResource;

class TransactionRepository extends BaseRepository
{
    protected $model, $orderModel;

    function __construct(Transaction $loc, Order $order)
    {
        $this->model = $loc;
        $this->orderModel = $order;
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
                'amount' => $data['amount'],
            ]);
            Log::info($record);
            foreach ($data['orders'] as $order) {
                $this->orderModel->create([
                    'transaction_number' => $record->transaction_number,
                    'name'        => $order['name'],
                    'barcode'     => $order['barcode'],
                    'type'        => $order['type'],
                    'quantity'    => $order['quantity'],
                    'price'       => $order['price'],
                    'total_price' => $order['total_price'],
                ]);
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
}
