<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'date'   => Carbon::parse($this->created_at)->toDateString(),
            'orders' => OrderResource::collection($this->orders),
            'transaction_number' => $this->transaction_number,
        ];
    }
}
