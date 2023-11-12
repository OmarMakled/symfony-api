<?php

namespace App\Tests\Service\Validator;

use App\Service\Validator\Constraints\StrongPassword;
use App\Service\Validator\Constraints\StrongPasswordValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class StrongPasswordValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new StrongPasswordValidator();
    }

    public function testValidPassword()
    {
        $this->validator->validate('Password123', new StrongPassword());
        self::assertNoViolation();
    }

    /**
     * @dataProvider getInvalidPasswords
     */
    public function testInvalidPasswords($password)
    {
        $constraint = new StrongPassword();

        $this->validator->validate($password, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ min }}', $constraint->min)
            ->setParameter('{{ max }}', $constraint->max)
        ->assertRaised();
    }

    public function getInvalidPasswords()
    {
        return [
            ['pass'],
            ['toolongpasstoolongpasstoolongpasstoolongpasstoolongpasstoolongpasstoolongpass'],
            ['onlyletters'],
        ];
    }
}
