<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
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
            'name'          => $this->product->name,
            'barcode'       => $this->barcode,
            'type'          => $this->type,
            'quantity'      => $this->quantity,
            'price'         => $this->price,
            'total_price'   => $this->total_price,
            'total_item'    => $this->total_item,
            'date'          => Carbon::parse($this->created_at)->toDateString(),
            'transaction_number' => $this->transaction_number,
        ];
    }
}
