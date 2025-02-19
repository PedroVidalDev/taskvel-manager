<?php

namespace App\Http\Requests\Task;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTaskRequest extends FormRequest
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
            'title' => ['string', 'max:255'],
            'description' => ['string'],
            'status' => ['string', new Enum(StatusEnum::class)],
            'priority' => ['integer', 'min:1', 'max:5'],
            'due_date' => ['date'],
        ];
    }
}
