<?php

namespace App\Common\Repository\MongoDB;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use ReflectionClass;
use ReflectionProperty;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 * @method object findOneById(string $id)
 * @method string getDocumentName()
 * @method DocumentManager getDocumentManager()
 */
trait UpdateOneTrait
{
    protected array $lastChanges = [
        'id' => null,
        'fields' => [],
    ];

    public function updateOne(string|object $idOrObject, object $updatable, $transactional = false): object|false
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
            $entityClass = $namespaceParts . '\\MongoDB\\' . $entityClass;

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
            $value = $property->getValue($updatable);

            if ($value instanceof NotIncludedInBody) {
                continue;
            }

            if (str_ends_with($property->getName(), 'Id')) {
                $relatedEntityProperty = preg_replace('/Id$/', '', $property->getName());
                $relatedEntity = $extractRelatedEntity($reflection, $property);

                $entity->{'set' . ucfirst($relatedEntityProperty)}($relatedEntity);

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
