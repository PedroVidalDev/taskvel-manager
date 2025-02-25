<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Rules\NotExistsByColumn;
use App\Rules\NotExistsByColumnWithoutScopes;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => ["required", "string", "min:3", "max:255"],
            'email' => ["required", "string", "email", new NotExistsByColumnWithoutScopes("User", User::class, "email")],
            'password' => ["required", "string", "min:8"],
        ];
    }
}
