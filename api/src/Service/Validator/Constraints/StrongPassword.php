<?php

namespace App\Service\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class StrongPassword extends Constraint
{
    public string $message = 'The password must be between {{ min }} and {{ max }} characters and contain at least one number.';
    public int $min = 6;
    public int $max = 50;
}
