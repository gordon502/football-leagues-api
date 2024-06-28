<?php

namespace App\Common\Repository\MariaDB;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Common\Repository\FindableByIdInterface;
use App\Modules\User\Model\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 * @method object|null findOneById(string $id)
 * @method string getEntityName()
 * @method EntityManagerInterface getEntityManager()
 * @property UserPasswordHasherInterface $passwordHasher
 */
trait UpdateOneTrait
{
    public function updateOne(string|object $idOrObject, object $updatable, bool $transactional = false): object|false
    {
        $extractRelatedEntities = function (
            ReflectionClass $reflection,
            ReflectionProperty $property
        ) use ($updatable): object|array|null {
            if (!$reflection->hasMethod('get' . ucfirst($property->getName()))) {
                return null;
            }

            $method = $reflection->getMethod('get' . ucfirst($property->getName()));
            $attributes = $method->getAttributes(DtoPropertyRelatedToEntity::class);

            if (count($attributes) === 0) {
                return null;
            }

            /** @var DtoPropertyRelatedToEntity $attribute */
            $attribute = $attributes[0]->newInstance();
            $entityInterfaceClass = $attribute->entityInterface;

            $namespaceParts = explode('\\', $entityInterfaceClass);
            $interface = array_pop($namespaceParts);
            $namespaceParts = implode('\\', $namespaceParts);

            $entityClass = preg_replace('/Interface$/', '', $interface);
            $entityClass = $namespaceParts . '\\MariaDB\\' . $entityClass;

            /** @var FindableByIdInterface $repository */
            $repository = $this->getEntityManager()->getRepository($entityClass);

            $propertyValue = $property->getValue($updatable);

            if ($propertyValue === null) {
                return null;
            }

            if (is_array($propertyValue)) {
                $foundEntities = [];
                foreach ($propertyValue as $singleId) {
                    $foundEntity = $repository->findById($singleId);

                    if (!$foundEntity) {
                        throw new RelatedEntityNotFoundException();
                    }

                    $foundEntities[] = $foundEntity;
                }

                return $foundEntities;
            }

            $foundEntity = $repository->findById($propertyValue);

            if (!$foundEntity) {
                throw new RelatedEntityNotFoundException();
            }

            return $foundEntity;
        };

        $entity = is_string($idOrObject)
            ? $this->findOneById($idOrObject)
            : $idOrObject;

        $fieldsToUpdateCount = 0;

        $reflection = new ReflectionClass($updatable);

        foreach ($reflection->getProperties() as $property) {
            if ($property->getName() === 'id') {
                continue;
            }

            $value = $property->getValue($updatable);

            if ($value instanceof NotIncludedInBody) {
                continue;
            }

            if (str_ends_with($property->getName(), 'Id')) {
                $relatedEntityProperty = preg_replace('/Id$/', '', $property->getName());
                $relatedEntities = $extractRelatedEntities($reflection, $property);

                if (!is_array($relatedEntities)) {
                    $entity->{'set' . ucfirst($relatedEntityProperty)}($relatedEntities);
                } else {
                    $entity->{'clear' . ucfirst($relatedEntityProperty)}();
                    foreach ($relatedEntities as $relatedEntity) {
                        $entity->{'add' . ucfirst($relatedEntityProperty)}($relatedEntity);
                    }
                }

                $fieldsToUpdateCount++;
                continue;
            }

            if ($property->getName() === 'password') {
                /** @var UserInterface $entity */
                $entity->setPassword($this->passwordHasher->hashPassword($entity, $value));
                $fieldsToUpdateCount++;
                continue;
            }

            $entity->{'set' . ucfirst($property->getName())}($value);

            $fieldsToUpdateCount++;
        }

        if ($fieldsToUpdateCount === 0) {
            return false;
        }

        if (!$transactional) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    public function flushUpdateOne(): void
    {
        $this->getEntityManager()->flush();
    }
}
