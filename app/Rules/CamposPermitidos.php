<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CamposPermitidos implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    
    protected $allowedAttributes;

    public function __construct(array $allowedAttributes)
    {
        $this->allowedAttributes = $allowedAttributes;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($attribute, $this->allowedAttributes)) {
            $fail("El atributo :attribute no está permitido.");
        }
    }
}
