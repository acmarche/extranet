<?php

namespace AcMarche\Extranet\Command;

use AcMarche\Extranet\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'extranet:user',
    description: 'no',
)]
class ExtranetUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        new SymfonyStyle($input, $output);
        $user = $this->userRepository->find(1);
        $password = $this->userPasswordHasher->hashPassword($user, 'homer');
        $user->setPassword($password);
        $this->userRepository->flush();

        return Command::SUCCESS;
    }
}
