<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory, SearchableTrait, HasUuids, SoftDeletes;

    protected $table = 'sales';
    protected $guarded = ['id'];
    protected $searchable = [
        'columns' => [
            'sales.name' => 15,
            'sales.barcode' => 13,
            'sales.transaction_number' => 10
        ],
        'table_columns' => [
            'id'        => 'sales.id',
            'name'      => 'sales.name',
            'barcode'   => 'sales.barcode',
            'transaction_number' => 'sales.transaction_number',
            'type'      => 'sales.type',
            'quantity'  => 'sales.quantity',
            'price'     => 'sales.price',
            'total_price' => 'sales.total_price',
            'deleted_at' => 'sales.deleted_at',
            'created_at' => 'sales.created_at',
            'updated_at' => 'sales.updated_at',
        ],
        'groupBy' => [
            'id'        => 'sales.id',
            'name'      => 'sales.name',
            'barcode'   => 'sales.barcode',
            'transaction_number' => 'sales.transaction_number',
            'type'      => 'sales.type',
            'quantity'  => 'sales.quantity',
            'price'     => 'sales.price',
            'total_price' => 'sales.total_price',
            'deleted_at' => 'sales.deleted_at',
            'created_at' => 'sales.created_at',
            'updated_at' => 'sales.updated_at',
        ],
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'barcode', 'barcode');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_number', 'transaction_number');
    }
}
