<?php

namespace App\Common\AutowireFactory;

use App\Common\AutowireFactory\Exception\DatabaseImplementationEnvVariableInvalidException;
use App\Common\AutowireFactory\Exception\FolderParameterMissingException;
use Symfony\Component\DependencyInjection\ContainerInterface;

final readonly class AutowireFactory
{
    public function __construct(
        private ContainerInterface $serviceContainer
    ) {
    }
    public function usingDatabaseImplementation(string $namespaceWithFolderParameter): object
    {
        if (!str_contains($namespaceWithFolderParameter, '{DB_IMPL}')) {
            throw new FolderParameterMissingException();
        }

        return match ($_ENV['DATABASE_IMPLEMENTATION']) {
            'InMemory' => $this->serviceContainer->get(str_replace('{DB_IMPL}', 'InMemory', $namespaceWithFolderParameter)),
            'MySQL' => $this->serviceContainer->get(str_replace('{DB_IMPL}', 'MySQL', $namespaceWithFolderParameter)),
            'MongoDB' => $this->serviceContainer->get(str_replace('{DB_IMPL}', 'MongoDB', $namespaceWithFolderParameter)),
            default => throw new DatabaseImplementationEnvVariableInvalidException(),
        };
    }
}
