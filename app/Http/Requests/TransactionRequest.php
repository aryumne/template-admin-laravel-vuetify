<?php

namespace App\Http\Requests;

class TransactionRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'amount'         => ['required', 'numeric'],
            'orders'         => ['required', 'array', 'min:1'],
        ]);
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'amount' => 'Total bayar',
            'orders' => 'Daftar obat'
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            '*.required' => ':attribute ini harus diisi!',
            '*.numeric'   => ':attribute ini harus angka!',
            '*.array'   => ':attribute ini tidak valid!',
            '*.min'   => ':attribute harus berisi minimal 1!',
        ]);
    }
}
