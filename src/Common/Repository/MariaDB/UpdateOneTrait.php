<?php

namespace App\Common\Repository\MariaDB;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Common\Repository\FindableByIdInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use ReflectionClass;
use ReflectionProperty;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 * @method object|null findOneById(string $id)
 * @method string getEntityName()
 * @method EntityManagerInterface getEntityManager()
 */
trait UpdateOneTrait
{
    // TODO: handle referencing object, not only single properties
    public function updateOne(string $id, object $updatable): bool
    {
        $extractRelatedEntity = function (
            ReflectionClass $reflection,
            ReflectionProperty $property
        ) use ($updatable): object|null {
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

            $foundEntity = $repository->findById($property->getValue($updatable));

            if (!$foundEntity) {
                throw new RelatedEntityNotFoundException();
            }

            return $foundEntity;
        };

        $qb = $this
            ->createQueryBuilder('uo')
            ->update($this->getEntityName(), 'uo')
            ->where('uo.id = :id')
            ->setParameter('id', $id);

        $fieldsToUpdateCount = 0;

        $reflection = new ReflectionClass($updatable);

        foreach ($reflection->getProperties() as $property) {
            $value = $property->getValue($updatable);

            if ($value instanceof NotIncludedInBody) {
                continue;
            }

            if (str_ends_with($property->getName(), 'Id')) {
                $relatedEntityProperty = preg_replace('/Id$/', '', $property->getName());
                $relatedEntity = $extractRelatedEntity($reflection, $property);

                $qb
                    ->set("uo.{$relatedEntityProperty}", ":{$relatedEntityProperty}")
                    ->setParameter(
                        $relatedEntityProperty,
                        $relatedEntity
                    );

                $fieldsToUpdateCount++;
                continue;
            }

            $qb
                ->set("uo.{$property->getName()}", ":{$property->getName()}")
                ->setParameter($property->getName(), $value);

            $fieldsToUpdateCount++;
        }

        if ($fieldsToUpdateCount === 0) {
            return false;
        }

        $qb->getQuery()->execute();

        return true;
    }
}
