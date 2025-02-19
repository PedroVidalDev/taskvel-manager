<?php

namespace App\Http\Requests\Comment;

use App\Models\Task;
use App\Models\User;
use App\Rules\ExistsByTaskId;
use App\Rules\ExistsById;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
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
            'content' => ['string', 'max:255'],
            'user_id' => ['integer', new ExistsById("User", User::class)],
            'task_id' => ['integer', new ExistsById("Task", Task::class)],
        ];
    }
}
