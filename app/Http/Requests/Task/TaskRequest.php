<?php

namespace App\Http\Requests\Task;

use App\Enums\StatusEnum;
use App\Models\Project;
use App\Models\TaskStatus;
use App\Models\User;
use App\Rules\ExistsByColumn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'integer', new ExistsByColumn("TaskStatus", TaskStatus::class, "id")],
            'priority' => ['required', 'integer', 'min:1', 'max:5'],
            'due_date' => ['required', 'date'],
            'project_id' => ['required', 'integer', new ExistsByColumn("Project", Project::class, "id")],
        ];
    }
}
