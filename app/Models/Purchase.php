<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory, SearchableTrait, HasUuids, SoftDeletes;

    protected $table = 'purchases';
    protected $guarded = ['id'];
    protected $searchable = [
        'columns' => [
            'purchases.name' => 15
        ],
        'table_columns' => [
            'id' => 'purchases.id',
            'name' => 'purchases.name',
            'quantity' => 'purchases.quantity',
            'product_id' => 'purchases.product_id',
            'price' => 'purchases.price',
            'total_price' => 'purchases.total_price',
            'deleted_at' => 'purchases.deleted_at',
            'created_at' => 'purchases.created_at',
            'updated_at' => 'purchases.updated_at',
        ],
        'groupBy' => [
            'id' => 'purchases.id',
            'name' => 'purchases.name',
            'quantity' => 'purchases.quantity',
            'product_id' => 'purchases.product_id',
            'price' => 'purchases.price',
            'total_price' => 'purchases.total_price',
            'deleted_at' => 'purchases.deleted_at',
            'created_at' => 'purchases.created_at',
            'updated_at' => 'purchases.updated_at',
        ],
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
