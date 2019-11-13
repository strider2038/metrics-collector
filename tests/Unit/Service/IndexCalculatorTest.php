<?php
/*
 * This file is part of metrics-collector.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Unit\Service;

use App\Contracts\IndexedEntityInterface;
use App\Service\IndexCalculator;
use PHPUnit\Framework\TestCase;
use Ramsey\Collection\Collection;
use Ramsey\Collection\CollectionInterface;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class IndexCalculatorTest extends TestCase
{
    /** @test */
    public function getOrderedCollection_collectionWithoutReferencedEntity_orderedCollectionReturned(): void
    {
        $calculator = new IndexCalculator();
        $entities = $this->givenCollection([
            $this->givenIndexedEntity(0),
            $this->givenIndexedEntity(2),
            $this->givenIndexedEntity(5),
        ]);
        $reference = $this->givenIndexedEntity(6);

        $orderedEntities = $calculator->getOrderedCollection($entities, $reference);

        $expectedIndexes = [0, 1, 2, 3];
        $this->assertEntitiesHasExpectedIndexes($orderedEntities, $expectedIndexes);
    }

    /** @test */
    public function getOrderedCollection_collectionWithReferencedEntity_orderedCollectionReturned(): void
    {
        $calculator = new IndexCalculator();
        $reference = $this->givenIndexedEntity(2);
        $entities = $this->givenCollection([
            $this->givenIndexedEntity(6),
            $reference,
            $this->givenIndexedEntity(2),
        ]);

        $orderedEntities = $calculator->getOrderedCollection($entities, $reference);

        $this->assertSame($reference, $orderedEntities[1]);
        $expectedIndexes = [0, 1, 2];
        $this->assertEntitiesHasExpectedIndexes($orderedEntities, $expectedIndexes);
    }

    private function givenIndexedEntity(int $index): IndexedEntityInterface
    {
        $entity = new class implements IndexedEntityInterface
        {
            /** @var int */
            private $index;

            public function setOrderIndex(int $orderIndex): void
            {
                $this->index = $orderIndex;
            }

            public function getOrderIndex(): int
            {
                return $this->index;
            }
        };
        $entity->setOrderIndex($index);

        return $entity;
    }

    private function givenCollection(array $entities): Collection
    {
        return new Collection(IndexedEntityInterface::class, $entities);
    }

    private function assertEntitiesHasExpectedIndexes(CollectionInterface $orderedEntities, array $expectedIndexes): void
    {
        $indexes = [];

        /** @var IndexedEntityInterface $entity */
        foreach ($orderedEntities as $entity) {
            $indexes[] = $entity->getOrderIndex();
        }

        $this->assertSame($expectedIndexes, $indexes);
    }
}
