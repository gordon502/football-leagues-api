<?php

namespace App\Modules\OrganizationalUnit\Repository\MongoDB;

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

    private readonly OrganizationalUnitFactoryInterface $organizationalUnitFactory;

    public function __construct(DocumentManager $dm, OrganizationalUnitFactoryInterface $organizationalUnitFactory)
    {
        $this->organizationalUnitFactory = $organizationalUnitFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(OrganizationalUnit::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(
        OrganizationalUnitCreatableInterface $organizationalUnitCreatable
    ): OrganizationalUnitInterface {
        /** @var OrganizationalUnit $organizationalUnit */
        $organizationalUnit = $this->organizationalUnitFactory->create(
            $organizationalUnitCreatable,
            OrganizationalUnit::class
        );

        $this->getDocumentManager()->persist($organizationalUnit);
        $this->getDocumentManager()->flush();

        return $organizationalUnit;
    }

    public function findById(string $id): ?OrganizationalUnitInterface
    {
        $organizationalUnit = $this->find($id);

        if ($organizationalUnit !== null) {
            $this->getDocumentManager()->refresh($organizationalUnit);
        }

        return $organizationalUnit;
    }

    public function delete(string $id): void
    {
        $this->createQueryBuilder()
            ->remove()
            ->field('id')->equals($id)
            ->getQuery()
            ->execute();
    }
}
