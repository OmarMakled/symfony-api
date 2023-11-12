<?php

namespace App\Command;

use DateTimeImmutable;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use App\Service\Messages\ActivationMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendActivationEmailsCommand extends Command
{
    protected static $defaultName = 'app:send-activation-emails';
    protected static string $defaultDescription = 'Send activation emails to active users created in the specified time period';

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly MailerInterface $mailer,
        private readonly ActivationMessage $message,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('duration', InputArgument::OPTIONAL, 'Time period for user creation, e.g., -- "-1 week"', '-1 week');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info(self::$defaultName . ' ... running');

        $date = (new DateTimeImmutable())->modify($input->getArgument('duration'));
        $activeUsers = $this->getActiveUsers($date);

        foreach ($activeUsers as $user) {
            $this->mailer->send($this->message->to($user));
        }

        $output->writeln($msg = sprintf(
            'Activation emails sent successfully for [%s] users created since [%s].',
            count($activeUsers),
            $date->format('Y-m-d')
        ));

        $this->logger->info($msg);
        $this->logger->info(self::$defaultName . ' .... completed');
        return 0;
    }

    private function getActiveUsers(DateTimeImmutable $date)
    {
        return $this->userRepository->findActiveUsersSinceDate($date);
    }
}
