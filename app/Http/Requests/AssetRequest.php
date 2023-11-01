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
            'asset_type_id' => 'required',
            'room_id' => 'required',
            'item_code' => 'required',
            'name' => 'required',
            'acquition' => 'required',
            'total' => 'required',
            'last_move_date' => 'required',
            'condition' => 'required',
            'note' => 'nullable',
        ];
    }
}
