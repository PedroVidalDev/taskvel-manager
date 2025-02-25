<?php

namespace App\Rules;

use App\Models\Scopes\ActiveScope;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotExistsByColumnWithoutScopes implements ValidationRule
{

    protected $name;
    protected $model;
    protected $column;

    public function __construct($name, $model, $column)
    {
        $this->name = $name;
        $this->model = $model;
        $this->column = $column;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->model::withoutGlobalScope(ActiveScope::class)->where($this->column, $value)->exists()) {
            $fail("The {$this->name} with column $this->column $value already exist.");
        }
    }
}
