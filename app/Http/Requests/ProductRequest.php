<?php

namespace App\Http\Requests;

class ProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name'              => ['required', 'string', 'unique:App\Models\Product,name'],
            'batch_number'      => ['nullable', 'string', 'unique:App\Models\Product,batch_number'],
            'stok_by_pack'      => ['nullable', 'number'],
            'stok_by_item'      => ['nullable', 'number'],
            'total_item'        => ['nullable', 'number'],
            'pack_price'        => ['required', 'number'],
            'item_price'        => ['required', 'number'],
            'product_type_id'   => ['required', 'exists:App\Models\BlogType,id'],
        ]);
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'name'            => 'Nama Obat',
            'batch_number'    => 'Nomor Batch',
            'stok_by_pack'    => 'Stok per Box',
            'stok_by_item'    => 'Stok per Pcs',
            'total_item'      => 'Total pcs',
            'pack_price'      => 'Harga per Box',
            'item_price'      => 'Harga satuan',
            'product_type_id' => 'Jenis obat',
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            '*.required' => ':attribute ini harus diisi!',
            '*.number'   => ':attribute ini harus angka!',
            '*.unique'   => ':attribute ini sudah terdaftar!',
        ]);
    }
}
