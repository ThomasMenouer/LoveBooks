<?php

namespace App\Application\Users\Command;

use App\Domain\Users\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Création d\'un utilisateur',
)]
class CreateUserCommand extends Command
{   
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription("Créer un nouvel utilisateur.")
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe de l\'utilisateur')
            ->addArgument('role', InputArgument::REQUIRED, 'Role de l\'utilisateur : ROLE_USER')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // On récupère les arguments
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = strtoupper($input->getArgument('role')); // On force la mise en majuscule

        // On créé l'utilisateur
        $user = new Users();

        $user->setEmail($email);
        $user->setRoles([$role]);

        $encodedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('l\'utilisateur ' . $email . ' et ' . $role . ' à été créé avec succès.');

        return Command::SUCCESS;
    }
}
