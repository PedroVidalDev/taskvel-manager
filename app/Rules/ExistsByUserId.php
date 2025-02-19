<?php

namespace App\Rules;

use App\Models\Task;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistsByUserId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!User::where('id', $value)->exists()) {
            $fail("The user with id $value does not exist.");
        }
    }
}
