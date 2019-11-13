<?php
/*
 * This file is part of metrics-collector.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Contracts\IndexedEntityInterface;
use Ramsey\Collection\CollectionInterface;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class IndexCalculator
{
    public function getOrderedCollection(CollectionInterface $entities, IndexedEntityInterface $referencedEntity): CollectionInterface
    {
        $orderedEntities = clone $entities;

        if (!$entities->contains($referencedEntity)) {
            $orderedEntities->add($referencedEntity);
        }

        $orderedEntities = $orderedEntities->sort('getOrderIndex');

        /**
         * @var IndexedEntityInterface $entity
         */
        foreach ($orderedEntities as $index => $entity) {
            $entity->setOrderIndex($index);
        }

        return $orderedEntities;
    }
}
