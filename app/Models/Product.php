<?php

namespace App\Models;

use App\Models\ProductType;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes, SearchableTrait, HasUuids;

    protected $table      = "products";
    protected $guarded    = ["id", "slug"];
    protected $searchable = [
        "columns" => [
            "products.name" => 15,
            "products.barcode" => 13,
            "products.batch_number" => 10,
        ],
        "table_columns" => [
            "id"            => "products.id",
            "slug"          => "products.slug",
            "name"          => "products.name",
            "batch_number"  => "products.batch_number",
            "barcode"       => "products.barcode",
            "stok_by_pack"  => "products.stok_by_pack",
            "stok_by_item"  => "products.stok_by_item",
            "pack_price"    => "products.pack_price",
            "item_price"    => "products.item_price",
            "total_item"    => "products.total_item",
            "product_type_id" => "products.product_type_id",
            "created_at"    => "products.created_at",
            "updated_at"    => "products.updated_at",
            "deleted_at"    => "products.deleted_at",
        ],
        "groupBy" => [
            "id"            => "products.id",
            "slug"          => "products.slug",
            "name"          => "products.name",
            "batch_number"  => "products.batch_number",
            "barcode"       => "products.barcode",
            "stok_by_pack"  => "products.stok_by_pack",
            "stok_by_item"  => "products.stok_by_item",
            "pack_price"    => "products.pack_price",
            "item_price"    => "products.item_price",
            "total_item"    => "products.total_item",
            "product_type_id" => "products.product_type_id",
            "created_at"    => "products.created_at",
            "updated_at"    => "products.updated_at",
            "deleted_at"    => "products.deleted_at",
        ],
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
    }
}
