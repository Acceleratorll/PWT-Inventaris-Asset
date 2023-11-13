<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'asset_id' => 'required',
            'room_id.*' => 'required|exists:rooms,id',
            'qty_good.*' => 'required|integer',
            'qty_bad.*' => 'required|integer',
            'total' => 'required|integer',
        ];
    }
}
