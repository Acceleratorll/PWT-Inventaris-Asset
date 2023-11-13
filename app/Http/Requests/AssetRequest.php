<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'item_code' => 'required|string',
            'total' => 'required|integer',
            'acquition' => 'required|date',
            'room_id.*' => 'required|exists:rooms,id',
            'asset_type_id' => 'required|exists:asset_types,id',
            'note' => 'nullable|string',
            'qty_good.*' => 'required|integer',
            'qty_bad.*' => 'required|integer',
            'last_move_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama asset harus diisi.',
            'item_code.required' => 'Kode asset harus diisi.',
            'total.required' => 'Total asset harus diisi.',
            'total.integer' => 'Total asset harus berupa angka.',
            'acquition.required' => 'Tanggal penerimaan asset harus diisi.',
            'acquition.date' => 'Tanggal penerimaan asset harus berupa tanggal.',
            'last_move_date.required' => 'Tanggal terakhir pindah asset harus diisi.',
            'last_move_date.date' => 'Tanggal terakhir pindah asset harus berupa tanggal.',
            'room_id.*.required' => 'Pilih setidaknya satu ruangan.',
            'room_id.*.exists' => 'Ruangan yang dipilih tidak valid.',
            'asset_type_id.required' => 'Pilih tipe asset.',
            'asset_type_id.exists' => 'Tipe asset yang dipilih tidak valid.',
            'note.string' => 'Catatan harus berupa teks.',
            'qty_good.*.required' => 'Jumlah asset yang baik harus diisi.',
            'qty_good.*.integer' => 'Jumlah asset yang baik harus berupa angka.',
            'qty_bad.*.required' => 'Jumlah asset yang buruk harus diisi.',
            'qty_bad.*.integer' => 'Jumlah asset yang buruk harus berupa angka.',
            'total_validation' => 'Total Asset dengan inputan tidak sama',
        ];
    }
}
