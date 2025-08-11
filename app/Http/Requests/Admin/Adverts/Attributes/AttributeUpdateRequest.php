<?php

namespace App\Http\Requests\Admin\Adverts\Attributes;

use App\Models\Adverts\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeUpdateRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'type' => [
                'required',
                'string',
                'max:255',
                Rule::in(array_keys(Attribute::typesList())),
            ],
            'required' => [
                'nullable',
                'string',
                'max:255',
            ],
            'variants' => [
                'nullable',
                'string',
            ],
            'sort' => [
                'required',
                'integer',
            ]
        ];
    }
}
