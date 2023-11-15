<?php

namespace App\Service\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class StrongPassword extends Constraint
{
    public string $message = 'password.strong';
    public int $min = 6;
    public int $max = 50;
}
