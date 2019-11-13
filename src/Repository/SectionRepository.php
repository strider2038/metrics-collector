<?php

namespace App\Repository;

use App\Entity\Section;
use App\Entity\SectionCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Section::class);
    }

    public function findFirstSection(): ?Section
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->orderBy('s.orderIndex', 'ASC');
        $queryBuilder->setMaxResults(1);
        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @return SectionCollection
     */
    public function findAllOrderedByIndex(): SectionCollection
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->orderBy('s.orderIndex', 'ASC');
        $query = $queryBuilder->getQuery();
        $rows = $query->getResult();

        return new SectionCollection($rows);
    }
}
