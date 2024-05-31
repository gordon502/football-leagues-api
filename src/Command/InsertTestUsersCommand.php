<?php

namespace App\Command;

use App\Modules\User\Model\MariaDB\User as MariaDBUser;
use App\Modules\User\Model\MongoDB\User as MongoDBUser;
use App\Modules\User\Role\UserRole;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Tests\Util\TestLoginUtil;

#[AsCommand(
    name: 'test:insert-users',
    description: 'Use this command to insert test users into MariaDB and MongoDB.',
)]
class InsertTestUsersCommand extends Command
{
    private readonly EntityManagerInterface $entityManager;
    private readonly DocumentManager $documentManager;
    private readonly UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        DocumentManager $documentManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->documentManager = $documentManager;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->createMariaDBUsers();
        $this->createMongoDBUsers();

        $io->success('Test users have been successfully inserted into databases.');

        return Command::SUCCESS;
    }

    private function createMariaDBUsers(): void
    {
        $user = new MariaDBUser();
        $user
            ->setEmail('admin@admin.com')
            ->setName('Admin')
            ->setPassword($this->passwordHasher->hashPassword($user, TestLoginUtil::DEFAULT_ADMIN_LIKE_PASSWORD))
            ->setRole(UserRole::ADMIN)
            ->setBlocked(false);
        $this->entityManager->persist($user);

        $user = new MariaDBUser();
        $user
            ->setEmail('moderator@moderator.com')
            ->setName('Moderator')
            ->setPassword($this->passwordHasher->hashPassword($user, TestLoginUtil::DEFAULT_ADMIN_LIKE_PASSWORD))
            ->setRole(UserRole::MODERATOR)
            ->setBlocked(false);
        $this->entityManager->persist($user);

        $user = new MariaDBUser();
        $user
            ->setEmail('editor@editor.com')
            ->setName('Editor')
            ->setPassword($this->passwordHasher->hashPassword($user, TestLoginUtil::DEFAULT_ADMIN_LIKE_PASSWORD))
            ->setRole(UserRole::EDITOR)
            ->setBlocked(false);
        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }

    private function createMongoDBUsers(): void
    {
        $admin = new MongoDBUser();
        $admin
            ->setEmail('admin@admin.com')
            ->setName('Admin')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'admin123!'))
            ->setRole(UserRole::ADMIN)
            ->setBlocked(false);
        $this->documentManager->persist($admin);

        $moderator = new MongoDBUser();
        $moderator
            ->setEmail('moderator@moderator.com')
            ->setName('Moderator')
            ->setPassword($this->passwordHasher->hashPassword($moderator, 'admin123!'))
            ->setRole(UserRole::MODERATOR)
            ->setBlocked(false);
        $this->documentManager->persist($moderator);

        $editor = new MongoDBUser();
        $editor
            ->setEmail('editor@editor.com')
            ->setName('Editor')
            ->setPassword($this->passwordHasher->hashPassword($editor, 'admin123!'))
            ->setRole(UserRole::EDITOR)
            ->setBlocked(false);
        $this->documentManager->persist($editor);

        $this->documentManager->flush();
    }
}
