<?php

namespace App\Http\Requests;

class ProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name'              => ['required', 'string', 'unique:App\Models\Product,name'],
            'batch_number'      => ['nullable', 'string', 'unique:App\Models\Product,batch_number'],
            'pack_stok'         => ['nullable', 'numeric'],
            'items_per_pack'    => ['nullable', 'numeric'],
            'total_item'        => ['nullable', 'numeric'],
            'pack_price'        => ['required', 'numeric'],
            'item_price'        => ['required', 'numeric'],
            'expired_date'      => ['required', 'date'],
            'product_type_id'   => ['required', 'exists:App\Models\ProductType,id']
        ]);
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'name'            => 'Nama Obat',
            'batch_number'    => 'Nomor Batch',
            'pack_stok'       => 'Stok per Box',
            'items_per_pack'  => 'Stok per Pcs',
            'total_item'      => 'Total pcs',
            'pack_price'      => 'Harga per Box',
            'item_price'      => 'Harga satuan',
            'product_type_id' => 'Jenis obat',
            'expired_date'    => 'Tanggal kedaluarsa'
        ]);
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            '*.required' => ':attribute ini harus diisi!',
            '*.numeric'  => ':attribute ini harus angka!',
            '*.unique'   => ':attribute ini sudah terdaftar!',
            '*.exists'   => ':attribute ini tidak ditemukan!',
            '*.date'     => ':attribute ini harus berupa tanggal'
        ]);
    }
}
