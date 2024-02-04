<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SearchableTrait, HasUuids;

    protected $table = 'orders';
    protected $guarded = ['id'];
    protected $searchable = [
        'columns' => [
            'orders.name' => 15,
            'orders.barcode' => 13,
            'orders.transaction_number' => 10
        ]
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
