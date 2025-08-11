<?php

namespace App\Http\Requests\Admin\Regions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegionUpdateRequest extends FormRequest
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
        $region = $this->route('region');

        return [
            'name' => [
              'required',
              'string',
              'max:255',
              Rule::unique('regions', 'name')
                  ->ignore($region->id, 'id')
                  ->where(function ($query) use ($region) {
                      return $query->where('parent_id', $region->parent_id);
                  }),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('regions', 'slug')
                    ->ignore($region->id)
                    ->where(function ($query) use ($region) {
                        return $query->where('parent_id', $region->parent_id);
                    }),
            ],

        ];
    }
}
