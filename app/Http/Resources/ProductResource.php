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
            'stok_by_pack'   => $this->stok_by_pack,
            'stok_by_item'   => $this->stok_by_item,
            'pack_price'     => $this->pack_price,
            'item_price'     => $this->item_price,
            'total_item'     => $this->total_item,
            'product_type'   => new ProductTypeResource($this->productType),
        ];
    }
}
