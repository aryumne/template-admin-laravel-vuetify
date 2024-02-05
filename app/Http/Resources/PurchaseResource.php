<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'product'       => $this->product,
            'name'          => $this->name,
            'quantity'      => $this->quantity,
            'price'         => $this->price,
            'total_price'   => $this->total_price,
        ];
    }
}
