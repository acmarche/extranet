<?php

namespace AcMarche\Extranet\Command;

use AcMarche\Taxe\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ExtranetUserCommand extends Command
{
    protected static $defaultName = 'extranet:user';
    protected static $defaultDescription = 'Add a short description for your command';
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        string $name = null,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user = $this->userRepository->find(1);
        $password = $this->userPasswordHasher->hashPassword($user, 'homer');
        $user->setPassword($password);
        $this->userRepository->flush();

        return Command::SUCCESS;
    }
}
