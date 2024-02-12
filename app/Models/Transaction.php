<?php

namespace App\Models;

use App\Models\Sales;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Transaction extends Model
{
    use HasFactory, HasUuids, SoftDeletes, SearchableTrait;
    protected $tabel = 'transactions';
    protected $guarded = ['id', 'transaction_number'];

    protected $searchable = [
        'columns' => [
            'transactions.transaction_number'  => 15,
            'transactions.prescription_number' => 13,
            'transactions.amount'        => 8,
            'transactions.cash_amount'   => 5,
            'transactions.return_amount' => 5,
        ],
        'table_columns' => [
            'id'            => 'transactions.id',
            'amount'        => 'transactions.amount',
            'cash_amount'   => 'transactions.cash_amount',
            'return_amount' => 'transactions.return_amount',
            'transaction_number'    => 'transactions.transaction_number',
            'prescription_number'   => 'transactions.prescription_number',
            'deleted_at' => 'transactions.deleted_at',
            'created_at' => 'transactions.created_at',
            'updated_at' => 'transactions.updated_at',
        ],
        'groupBy' => [
            'id'            => 'transactions.id',
            'amount'        => 'transactions.amount',
            'cash_amount'   => 'transactions.cash_amount',
            'return_amount' => 'transactions.return_amount',
            'transaction_number'    => 'transactions.transaction_number',
            'prescription_number'   => 'transactions.prescription_number',
            'deleted_at' => 'transactions.deleted_at',
            'created_at' => 'transactions.created_at',
            'updated_at' => 'transactions.updated_at',
        ],
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->transaction_number = 'TRX' . now()->format("ymdHis");
        });
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class, 'transaction_number', 'transaction_number');
    }
}
