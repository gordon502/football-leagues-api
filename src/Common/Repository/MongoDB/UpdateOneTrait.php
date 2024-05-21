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

    // TODO: this won't work with orphan removal and cascade delete, migrate it to EntityManager
    public function updateOne(string $id, object $updatable, $transactional = false): bool
    {
        $extractRelatedEntity = function (
            ReflectionClass $reflection,
            ReflectionProperty $property
        ) use ($updatable): array {
            if (!$reflection->hasMethod('get' . ucfirst($property->getName()))) {
                return [null, null];
            }

            $method = $reflection->getMethod('get' . ucfirst($property->getName()));
            $attributes = $method->getAttributes(DtoPropertyRelatedToEntity::class);

            if (count($attributes) === 0) {
                return [null, null];
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

            return [$foundEntity, $entityClass];
        };

        if ($transactional) {
            $oldEntity = $this->findById($id);
        }

        $qb = $this->createQueryBuilder('uo')
            ->updateOne()
            ->field('id')->equals($id);

        $fieldsToUpdateCount = 0;

        $reflection = new ReflectionClass($updatable);

        foreach ($reflection->getProperties() as $property) {
            $value = $property->getValue($updatable);

            if ($value instanceof NotIncludedInBody) {
                continue;
            }

            if (str_ends_with($property->getName(), 'Id')) {
                $relatedEntityProperty = preg_replace('/Id$/', '', $property->getName());
                list($relatedEntity, $entityClass) = $extractRelatedEntity($reflection, $property);

                if ($transactional) {
                    $this->lastChanges['fields'][$relatedEntityProperty] = [
                        'id' => $oldEntity->{'get' . ucfirst($relatedEntityProperty)}()->getId(),
                        'entityClass' => $entityClass,
                    ];
                }

                $qb->field($relatedEntityProperty)->set($relatedEntity);

                $fieldsToUpdateCount++;
                continue;
            }

            $qb->field($property->getName())->set($value);

            if ($transactional) {
                $this->lastChanges['fields'][$property->getName()] = $value;
            }

            $fieldsToUpdateCount++;
        }

        if ($fieldsToUpdateCount === 0) {
            return false;
        }

        $result = $qb->getQuery()->execute();

        return $result->getModifiedCount() > 0;
    }

    public function commitUpdateOne(): void
    {
        // Nothing to do in MongoDB
    }

    public function rollBackUpdateOne(): void
    {
        $qb = $this->createQueryBuilder('uo')
            ->updateOne()
            ->field('id')->equals($this->lastChanges['id']);

        foreach ($this->lastChanges['fields'] as $field => $value) {
            if (is_array($value)) {
                var_dump($value);
                $qb->field($field)->set($this->getDocumentManager()->find($value['entityClass'], $value['id']));
                continue;
            }

            $qb->field($field)->set($value);
        }

        $qb->getQuery()->execute();
    }
}
