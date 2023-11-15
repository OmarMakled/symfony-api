<?php

namespace App\Service\Validator;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    public ConstraintViolationListInterface $errors;

    public function __construct(private readonly ValidatorInterface $validator, private readonly TranslatorInterface $translator)
    {
    }


    public function isValid(object $obj): bool
    {
        $this->errors = $this->validator->validate($obj);

        return !$this->hasError();
    }

    public function hasError(): bool
    {
        return (count($this->errors) > 0);
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->errors as $violation) {
            $errors[$violation->getPropertyPath()][] = $this->translate(
                $violation->getMessage()
            );
        }

        return $errors;
    }

    private function translate(string $string): string
    {
        return $this->translator->trans($string, [], 'validators');
    }
}
