<?php

namespace App\Repository;

use App\Entity\Metric;
use App\Entity\MetricCollection;
use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Collection\CollectionInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Metric|null find($id, $lockMode = null, $lockVersion = null)
 * @method Metric|null findOneBy(array $criteria, array $orderBy = null)
 * @method Metric[]    findAll()
 * @method Metric[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetricRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Metric::class);
    }

    public function findBySectionOrderedByIndex(Section $section): MetricCollection
    {
        $queryBuilder = $this->createQueryBuilder('m');
        $queryBuilder->where('m.section = :section');
        $queryBuilder->orderBy('m.orderIndex', 'ASC');
        $queryBuilder->setParameter('section', $section);
        $query = $queryBuilder->getQuery();
        $rows = $query->getResult();

        return new MetricCollection($rows);
    }
}
