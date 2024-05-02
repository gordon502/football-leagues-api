<?php

namespace App\Common\Repository\MariaDB;

use App\Common\Dto\NotIncludedInBody;
use Doctrine\ORM\QueryBuilder;
use ReflectionClass;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 * @method object|null findOneById(string $id)
 * @method string getEntityName()
 */
trait UpdateOneTrait
{
    public function updateOne(string $id, object $updatable): bool
    {
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
