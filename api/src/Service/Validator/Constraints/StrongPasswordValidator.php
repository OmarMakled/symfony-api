<?php

namespace App\Service\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StrongPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        // Check if the password meets the strength criteria
        if (
            strlen($value) < $constraint->min ||
            strlen($value) > $constraint->max ||
            !preg_match('/\d/', $value)
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ min }}', $constraint->min)
                ->setParameter('{{ max }}', $constraint->max)
                ->addViolation();
        }
    }
}
