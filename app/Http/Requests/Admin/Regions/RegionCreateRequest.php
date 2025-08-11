<?php

namespace App\Http\Requests\Admin\Regions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegionCreateRequest extends FormRequest
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
        $regionId = $this->route('region') ?? null;

        return [
            'name' => [
              'required',
              'string',
              'max:255',
              Rule::unique('regions', 'name')->where(function ($query) {
                if ($this->has('parent_id')) {
                    return $query->where('parent_id', $this->input('parent_id'));
                } else {
                    return $query->whereNull('parent_id');
                }
              })->ignore($regionId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('regions', 'slug')->where(function ($query) {
                    if ($this->has('slug')) {
                        return $query->where('slug', $this->input('slug'));
                    } else {
                        return $query->whereNull('slug');
                    }
                })
            ],
            'parent' => [
                'nullable',
                'exists:regions,id',
            ]
        ];
    }
}
