<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Chỉ chấp nhận địa chỉ email cá nhân @gmail.com (không dùng @googlemail.com hay domain khác).
 */
class GmailAddress implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail(__('validation.email', ['attribute' => __('validation.attributes.email')]));

            return;
        }

        $email = trim($value);

        if ($email === '') {
            $fail(__('validation.required', ['attribute' => __('validation.attributes.email')]));

            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $fail(__('validation.email', ['attribute' => __('validation.attributes.email')]));

            return;
        }

        $parts = explode('@', $email, 2);
        if (count($parts) !== 2 || strtolower($parts[1]) !== 'gmail.com') {
            $fail(__('validation.gmail_only'));

            return;
        }

        if (str_contains($parts[0], '..') || str_starts_with($parts[0], '.') || str_ends_with($parts[0], '.')) {
            $fail(__('validation.email', ['attribute' => __('validation.attributes.email')]));
        }
    }
}
