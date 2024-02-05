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
