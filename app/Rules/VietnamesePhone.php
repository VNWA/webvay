<?php

namespace App\Rules;

use App\Support\VnPhone;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VietnamesePhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || trim($value) === '') {
            $fail(__('validation.required', ['attribute' => __('validation.attributes.phone')]));

            return;
        }

        $normalized = VnPhone::normalize($value);

        if (! VnPhone::isValidNormalized($normalized)) {
            $fail(__('validation.phone_vn'));
        }
    }
}
