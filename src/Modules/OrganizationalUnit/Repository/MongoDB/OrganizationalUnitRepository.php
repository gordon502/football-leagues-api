<?php

namespace App\Modules\OrganizationalUnit\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\OrganizationalUnit\Factory\OrganizationalUnitFactoryInterface;
use App\Modules\OrganizationalUnit\Model\MongoDB\OrganizationalUnit;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitCreatableInterface;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\OrganizationalUnit\Repository\OrganizationalUnitRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class OrganizationalUnitRepository extends DocumentRepository implements OrganizationalUnitRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly OrganizationalUnitFactoryInterface $organizationalUnitFactory;

    public function __construct(DocumentManager $dm, OrganizationalUnitFactoryInterface $organizationalUnitFactory)
    {
        $this->organizationalUnitFactory = $organizationalUnitFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(OrganizationalUnit::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(object $object): OrganizationalUnitInterface
    {
        if (!$object instanceof OrganizationalUnitCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . OrganizationalUnitCreatableInterface::class
            );
        }

        $organizationalUnit = $this->organizationalUnitFactory->create(
            $object,
            OrganizationalUnit::class
        );

        $this->getDocumentManager()->persist($organizationalUnit);
        $this->getDocumentManager()->flush();

        return $organizationalUnit;
    }

    public function findById(string $id): ?OrganizationalUnitInterface
    {
        return $this->find($id);
    }
}
