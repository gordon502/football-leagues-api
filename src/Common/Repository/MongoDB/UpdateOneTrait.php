<?php

namespace App\Common\Repository\MongoDB;

use App\Common\Dto\NotIncludedInBody;
use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use ReflectionClass;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 * @method object findOneById(string $id)
 * @method string getDocumentName()
 */
trait UpdateOneTrait
{
    public function updateOne(string $id, object $updatable): bool
    {
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

            $qb->field($property->getName())->set($value);

            $fieldsToUpdateCount++;
        }

        if ($fieldsToUpdateCount === 0) {
            return false;
        }

        $result = $qb->getQuery()->execute();

        return $result->getModifiedCount() > 0;
    }
}
