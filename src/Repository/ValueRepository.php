<?php

namespace App\Repository;

use App\Entity\Metric;
use App\Entity\Value;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Value|null find($id, $lockMode = null, $lockVersion = null)
 * @method Value|null findOneBy(array $criteria, array $orderBy = null)
 * @method Value[]    findAll()
 * @method Value[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Value::class);
    }

    public function getAverageValuesPerDayByMetric(Metric $metric): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
                datetime(v.created_at, \'start of day\') AS day,
                AVG(value) AS value
            FROM value v
            WHERE v.metric_id = :metric
            GROUP BY day
            ORDER BY day DESC
        ';

        $statement = $connection->prepare($sql);
        $statement->execute(['metric' => $metric->getName()]);
        $rows = $statement->fetchAll();

        $values = [];

        foreach ($rows as $row) {
            $day = (new \DateTimeImmutable($row['day']))->format('Y-m-d');
            $values[$day] = $row['value'];
        }

        return $values;
    }
}
