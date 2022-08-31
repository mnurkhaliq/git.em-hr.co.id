<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Custom
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function startRangeTo($attribute, $value, $parameters, $validator)
    {
        return Carbon::parse($value)->gte(Carbon::parse($parameters[0])->subDays($parameters[1]));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function startRangeToMessage($message, $attribute, $rule, $parameters)
    {
        return 'Maximum date range is ' . $parameters[1] . '.';
    }
}
