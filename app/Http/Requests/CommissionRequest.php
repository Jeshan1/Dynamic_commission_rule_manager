<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommissionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rules' => 'required|array|min:1',
            'rules.*.origins' => 'required|array|min:1',
            'rules.*.origins.*.code' => 'required|string|max:10',
            'rules.*.destinations' => 'required|array|min:1',
            'rules.*.destinations.*.code' => 'required|string|max:10',
            'rules.*.rate_value' => 'required|numeric|min:0',
            'rules.*.rate_type' => 'required|in:percentage,flat',
        ];
    }
}
