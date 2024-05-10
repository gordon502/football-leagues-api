<?php

namespace App\Common\Repository\MongoDB;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * @method object findById(string $id)
 * @method DocumentManager getDocumentManager()
 */
trait DeleteTrait
{
    public function delete(string $id): void
    {
        $entity = $this->findById($id);

        $this->getDocumentManager()->remove($entity);
        $this->getDocumentManager()->flush();
    }
}
