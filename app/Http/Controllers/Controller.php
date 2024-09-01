<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    public function validateRequestData($data, $rules, $messages = [], $customAttributes = [], $stopOnFirstFailure = true): array
    {
        $validator = Validator::make($data, $rules, $messages, $customAttributes)->stopOnFirstFailure($stopOnFirstFailure);
        if ($validator->fails()) {
            throw new ValidationException($validator, $validator->errors()->first());
        }

        return $validator->validated();
    }
}
