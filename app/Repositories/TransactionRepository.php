<?php

namespace App\Repositories;

use Exception;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Resources\TransactionResource;
use App\Models\Sales;

class TransactionRepository extends BaseRepository
{
    protected $model, $salesModel;

    function __construct(Transaction $loc, Sales $sales)
    {
        $this->model = $loc;
        $this->salesModel = $sales;
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
                $this->salesModel->create([
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
