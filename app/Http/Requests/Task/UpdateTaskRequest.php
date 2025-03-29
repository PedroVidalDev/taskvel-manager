<?php

namespace App\Http\Requests\Task;

use App\Models\TaskStatus;
use App\Rules\ExistsByColumn;
use Illuminate\Foundation\Http\FormRequest;

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
            'status' => ['integer', new ExistsByColumn("TaskStatus", TaskStatus::class, "id")],
            'priority' => ['integer', 'min:1', 'max:5'],
            'due_date' => ['date'],
        ];
    }
}
