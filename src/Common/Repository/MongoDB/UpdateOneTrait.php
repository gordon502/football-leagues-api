<?php

namespace App\Common\Repository\MongoDB;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\User\Model\UserInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use ReflectionClass;
use ReflectionProperty;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 * @method object findById(string $id)
 * @method string getDocumentName()
 * @method DocumentManager getDocumentManager()
 */
trait UpdateOneTrait
{
    public function updateOne(string|object $idOrObject, object $updatable, $transactional = false): object|false
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
            $entityClass = $namespaceParts . '\\MongoDB\\' . $entityClass;

            $propertyValue = $property->getValue($updatable);

            if ($propertyValue === null) {
                return null;
            }

            if (is_array($propertyValue)) {
                $foundEntities = [];
                foreach ($propertyValue as $singleId) {
                    // TODO: for now we cannot get repository because of broken DI while extending DocumentRepository
                    $foundEntity = $this->getDocumentManager()->find($entityClass, $singleId);

                    if (!$foundEntity) {
                        throw new RelatedEntityNotFoundException();
                    }

                    $foundEntities[] = $foundEntity;
                }

                return $foundEntities;
            }

            // TODO: for now we cannot get repository because of broken DI while extending DocumentRepository
            $foundEntity = $this->getDocumentManager()->find($entityClass, $property->getValue($updatable));

            if (!$foundEntity) {
                throw new RelatedEntityNotFoundException();
            }

            return $foundEntity;
        };

        $entity = is_string($idOrObject) ? $this->findById($idOrObject) : $idOrObject;

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
            $this->getDocumentManager()->flush();
        }

        return $entity;
    }

    public function flushUpdateOne(): void
    {
        $this->getDocumentManager()->flush();
    }
}
