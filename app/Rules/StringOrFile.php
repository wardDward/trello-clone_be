<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StringOrFile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value)) return;

        // File validation
        if (request()->hasFile($attribute)) {
            $file = request()->file($attribute);

            $allowedMimes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/jpg',
                'image/avif',
            ];

            if (!in_array($file->getMimeType(), $allowedMimes)) $fail('The file must be a JPG, PNG, WEBP, JPG, or AVIF.');
            if ($file->getSize() > 10 * 1024 * 1024) $fail('The file may not be greater than 10MB.');

            return;
        }

        $fail("The {$attribute} must be either a string or a file.");
    }
}
