<?php

namespace App\Service\Messages;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActivationMessage
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function to(User $user): Email
    {
        return (new Email())
            ->from(new Address('no-reply@cobbleweb.com', 'Cobbleweb'))
            ->to($user->getEmail())
            ->subject($this->translator->trans('activation.subject'))
            ->text($this->translator->trans('activation.body'));
    }
}
