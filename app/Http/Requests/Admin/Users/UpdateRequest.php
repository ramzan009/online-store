<?php

namespace App\Http\Requests\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                Rule::unique(User::class, 'email')
                    ->ignore($this->route('user')->id)
            ],
            'role' => [
                'required',
                'string',
                Rule::in(array_keys(User::rolesList())),
            ]
        ];
    }
}
