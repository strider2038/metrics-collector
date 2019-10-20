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

use App\Entity\Metric;
use App\Entity\MetricCollection;
use App\Entity\Section;
use App\Repository\MetricRepository;
use App\Repository\ValueRepository;
use App\Service\StatisticsReader;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class StatisticsReaderTest extends TestCase
{
    /** @var MetricRepository */
    private $metricRepository;

    /** @var ValueRepository */
    private $valueRepository;

    protected function setUp(): void
    {
        $this->metricRepository = \Phake::mock(MetricRepository::class);
        $this->valueRepository = \Phake::mock(ValueRepository::class);
    }

    /** @test */
    public function getDailySectionStatistics_given_expected(): void
    {
        $section = new Section();
        $reader = new StatisticsReader($this->metricRepository, $this->valueRepository);
        $metric1 = new Metric();
        $metric1->setName('metricName1');
        $metric2 = new Metric();
        $metric2->setName('metricName2');
        $this->givenMetricRepository_findBySectionOrderedByIndex_returnsMetrics($metric1, $metric2);
        $this->givenValueRepository_getAverageValuesPerDayByMetric_returnsValues($metric1, [
            '2019-08-06' => 47,
            '2019-08-05' => 37,
            '2019-08-02' => 41,
        ]);
        $this->givenValueRepository_getAverageValuesPerDayByMetric_returnsValues($metric2, [
            '2019-08-07' => 53,
            '2019-08-06' => 42,
            '2019-08-01' => 33,
        ]);

        $statistics = $reader->getDailySectionStatistics($section);

        $this->assertMetricRepository_findBySectionOrderedByIndex_wasCalledOnceWithSection($section);
        $this->assertValueRepository_getAverageValuesPerDayByMetric_wasCalledOnceWithMetric($metric1);
        $this->assertValueRepository_getAverageValuesPerDayByMetric_wasCalledOnceWithMetric($metric2);
        $this->assertSame(['metricName1', 'metricName2'], $statistics->headers);
        $this->assertSame(
            [
                '2019-08-07' => [
                    'metricName1' => [
                        'averageValue' => null,
                    ],
                    'metricName2' => [
                        'averageValue' => 53,
                    ],
                ],
                '2019-08-06' => [
                    'metricName1' => [
                        'averageValue' => 47,
                    ],
                    'metricName2' => [
                        'averageValue' => 42,
                    ],
                ],
                '2019-08-05' => [
                    'metricName1' => [
                        'averageValue' => 37,
                    ],
                    'metricName2' => [
                        'averageValue' => null,
                    ],
                ],
                '2019-08-04' => [
                    'metricName1' => [
                        'averageValue' => null,
                    ],
                    'metricName2' => [
                        'averageValue' => null,
                    ],
                ],
                '2019-08-03' => [
                    'metricName1' => [
                        'averageValue' => null,
                    ],
                    'metricName2' => [
                        'averageValue' => null,
                    ],
                ],
                '2019-08-02' => [
                    'metricName1' => [
                        'averageValue' => 41,
                    ],
                    'metricName2' => [
                        'averageValue' => null,
                    ],
                ],
                '2019-08-01' => [
                    'metricName1' => [
                        'averageValue' => null,
                    ],
                    'metricName2' => [
                        'averageValue' => 33,
                    ],
                ],
            ],
            $statistics->values->toPlainValues()
        );
    }

    private function assertMetricRepository_findBySectionOrderedByIndex_wasCalledOnceWithSection(Section $section): void
    { 
        \Phake::verify($this->metricRepository)
            ->findBySectionOrderedByIndex($section);
    }

    private function givenValueRepository_getAverageValuesPerDayByMetric_returnsValues(Metric $metric, array $values): void
    {
        \Phake::when($this->valueRepository)
            ->getAverageValuesPerDayByMetric($metric)
            ->thenReturn($values);
    }

    private function assertValueRepository_getAverageValuesPerDayByMetric_wasCalledOnceWithMetric(Metric $metric): void
    {
        \Phake::verify($this->valueRepository)
            ->getAverageValuesPerDayByMetric($metric);
    }

    private function givenMetricRepository_findBySectionOrderedByIndex_returnsMetrics(Metric ...$metrics): void
    {
        \Phake::when($this->metricRepository)
            ->findBySectionOrderedByIndex(\Phake::anyParameters())
            ->thenReturn(new MetricCollection($metrics));
    }
}
