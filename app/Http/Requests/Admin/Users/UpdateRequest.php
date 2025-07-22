<?php

namespace App\Http\Requests\Admin\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
              'unique:' . User::class,
            ],
            'email' => [
                'required',
                'email',
                Rule::unique(User::class, 'email')
                    ->where(function ($query) {
                        $query->where('id', '!=', Auth::user()->id);
                    })
            ],
            'status' => [
                'required',
                'string',
                Rule::in([User::STATUS_ACTIVE, User::STATUS_WAIT]),
            ]
        ];
    }
}
