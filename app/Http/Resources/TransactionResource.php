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
            'id'            => $this->id,
            'amount'        => $this->amount,
            'cash_amount'   => $this->cash_amount,
            'return_amount' => $this->return_amount,
            'orders'        => SalesResource::collection($this->sales),
            'date'          => Carbon::parse($this->created_at)->toDateString(),
            'prescription_number' => $this->prescription_number,
            'transaction_number'  => $this->transaction_number,
        ];
    }
}
