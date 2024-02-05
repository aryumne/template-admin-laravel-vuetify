<?php

namespace App\Http\Requests;

class PurchaseRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'stocks' => ['required', 'array', 'min:1']
        ]);
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'stocks' => 'Data pembelian',
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            '*.array'   => ':attribute ini tidak valid!',
            '*.min'   => ':attribute harus berisi minimal 1!',
        ]);
    }
}
