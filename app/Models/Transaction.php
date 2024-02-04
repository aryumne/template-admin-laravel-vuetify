<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, HasUuids;
    protected $tabel = 'transactions';
    protected $guarded = ['id', 'transaction_number'];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->transaction_number = 'TRX' . now()->format("ymdHis");
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'transaction_number', 'transaction_number');
    }
}
