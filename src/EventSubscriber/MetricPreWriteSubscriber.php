<?php
/*
 * This file is part of metrics-collector.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Contracts\IndexedEntityInterface;
use App\Entity\Metric;
use App\Repository\MetricRepository;
use App\Service\IndexCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class MetricPreWriteSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var IndexCalculator */
    private $indexCalculator;

    /** @var MetricRepository */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, IndexCalculator $indexCalculator)
    {
        $this->indexCalculator = $indexCalculator;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Metric::class);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['recalculateIndex', EventPriorities::PRE_WRITE],
        ];
    }

    public function recalculateIndex(ViewEvent $event): void
    {
        $metric = $event->getControllerResult();

        if ($metric instanceof Metric) {
            $metrics = $this->repository->findBySectionOrderedByIndex($metric->getSection());
            $metrics = $this->indexCalculator->getOrderedCollection($metrics, $metric);

            foreach ($metrics as $metric) {
                $this->entityManager->persist($metric);
            }

            $this->entityManager->flush();
        }
    }
}
