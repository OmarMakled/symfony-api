<?php

namespace App\Tests\Unit\Service\Messages;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Service\Messages\ActivationMessage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ActivationMessageTest extends WebTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testTo()
    {
        $translator = self::$container->get('translator');
        $user = (new User())->setEmail('user@example.com');
        $expectedEmail = (new Email())
            ->from(new Address('no-reply@cobbleweb.com', 'Cobbleweb'))
            ->to('user@example.com')
            ->subject($translator->trans('activation.subject'))
            ->text($translator->trans('activation.body'));

        self::assertEquals($expectedEmail, (new ActivationMessage($translator))->to($user));
    }
}
