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
use App\Entity\Metric;
use App\Entity\Section;
use App\Repository\MetricRepository;
use App\Repository\SectionRepository;
use App\Service\IndexCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class SectionPreWriteSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var IndexCalculator */
    private $indexCalculator;

    /** @var SectionRepository */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, IndexCalculator $indexCalculator)
    {
        $this->indexCalculator = $indexCalculator;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Section::class);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['recalculateIndex', EventPriorities::PRE_WRITE],
        ];
    }

    public function recalculateIndex(ViewEvent $event): void
    {
        $section = $event->getControllerResult();

        if ($section instanceof Section) {
            $sections = $this->repository->findAllOrderedByIndex();
            $sections = $this->indexCalculator->getOrderedCollection($sections, $section);

            foreach ($sections as $section) {
                $this->entityManager->persist($section);
            }

            $this->entityManager->flush();
        }
    }
}
