<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'slug'           => $this->slug,
            'name'           => $this->name,
            'batch_number'   => $this->batch_number,
            'barcode'        => $this->barcode,
            'pack_stok'      => $this->pack_stok,
            'items_per_pack' => $this->items_per_pack,
            'pack_price'     => $this->pack_price,
            'item_price'     => $this->item_price,
            'total_item'     => $this->total_item,
            'expired_date'   => $this->expired_date,
            'product_type'   => new ProductTypeResource($this->productType),
        ];
    }
}
