<?php

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Service\Messages\ActivationMessage;
use App\Command\SendActivationEmailsCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SendActivationEmailsCommandTest extends WebTestCase
{
    private $command;

    public function setUp(): void
    {
        self::bootKernel();

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $mailerMock = $this->createMock(MailerInterface::class);
        $activationMessageMock = $this->createMock(ActivationMessage::class);
        $loggerMock = $this->createMock(LoggerInterface::class);

        $user1 = (new User())->setEmail('foo@mail.com');
        $user2 = (new User())->setEmail('bar@mail.com');
        $userRepositoryMock
            ->expects($this->once())
            ->method('findActiveUsersSinceDate')
            ->willReturn([$user1, $user2]);
        $mailerMock
            ->expects($this->exactly(2))
            ->method('send');
        $activationMessageMock
            ->expects($this->exactly(2))
            ->method('to')
            ->willReturn(new Email());
        $this->command = new CommandTester(
            new SendActivationEmailsCommand($userRepositoryMock, $mailerMock, $activationMessageMock, $loggerMock)
        );
    }

    public function testExecute()
    {
        $this->command->execute([]);
        $output = $this->command->getDisplay();

        $date = ((new DateTimeImmutable())->modify('-1 week'))->format('Y-m-d');
        self::assertStringContainsString('Activation emails sent successfully for [2] users created since [' . $date . ']', $output);
    }

    public function testExecuteWithDuration()
    {
        $this->command->execute([
            'duration' => '-2 week'
        ]);
        $output = $this->command->getDisplay();

        $date = ((new DateTimeImmutable())->modify('-2 week'))->format('Y-m-d');
        self::assertStringContainsString('Activation emails sent successfully for [2] users created since [' . $date . ']', $output);
    }
}
