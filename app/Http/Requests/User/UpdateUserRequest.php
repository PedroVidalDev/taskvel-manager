<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Rules\NotExistsByColumn;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => ["string", "min:3", "max:255"],
            'password' => ["string", "min:8"],
        ];
    }
}
