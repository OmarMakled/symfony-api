<?php

namespace App\Service\Validator\Constraints;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        // Check if there's an authenticated user
        $currentUser = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;

        if (!$currentUser || !$currentUser instanceof User) {
            // If there's no authenticated user, it's a new registration
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $value]);
        } else {
            // If there's an authenticated user, exclude their email from the uniqueness check
            $existingUser = $this->entityManager
                ->getRepository(User::class)
                ->findByEmailExcludingCurrentId($value, $currentUser->getId());
        }

        if ($existingUser) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
