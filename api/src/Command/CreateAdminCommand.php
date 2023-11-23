<?php

namespace App\Command;

use App\DTO\UserDTO;
use App\Service\User\AdminRegistrationService;
use App\Service\Validator\ValidatorService;
use App\Service\User\UserRegistrationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(private readonly UserRegistrationService $userRegistrationService, private readonly AdminRegistrationService $adminRegistrationService, private readonly ValidatorService $validatorService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $firstName = $io->ask('Fist name:');
        $lastName = $io->ask('Last name:');
        $email = $io->ask('Email:');
        $passwordQuestion = new Question('Password:');
        $passwordQuestion->setHidden(true)
                        ->setHiddenFallback(false);
        $password = $io->askQuestion($passwordQuestion);

        $userDTO = UserDTO::createFromArray(compact('firstName', 'lastName', 'email', 'password'));
        if (!$this->validatorService->isValid($userDTO)) {
            $output->write(json_encode($this->validatorService->getErrors()));
            return 1;
        }
        $user = $this->userRegistrationService->create($userDTO);
        $this->adminRegistrationService->create($user);

        $output->write('Success ' . $user->getEmail());

        return 0;
    }
}
