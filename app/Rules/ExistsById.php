<?php

namespace App\Rules;

use App\Models\Task;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class ExistsById implements ValidationRule
{

    protected $name;
    protected $model;

    public function __construct($name, $model)
    {
        $this->name = $name;
        $this->model = $model;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->model::where('id', $value)->exists()) {
            $fail("The {$this->name} with id $value does not exist.");
        }
    }
}
