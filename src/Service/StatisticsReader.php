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

use App\Entity\Metric;
use App\Entity\Section;
use App\Repository\MetricRepository;
use App\Repository\ValueRepository;
use App\Statistics\DailyStatistics\Statistics;
use App\Statistics\DailyStatistics\Value;
use App\Statistics\DailyStatistics\ValuePerMetric;
use App\Statistics\DailyStatistics\ValuePerMetricPerDay;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class StatisticsReader
{
    /** @var MetricRepository */
    private $metricRepository;

    /** @var ValueRepository */
    private $valueRepository;

    public function __construct(MetricRepository $metricRepository, ValueRepository $valueRepository)
    {
        $this->metricRepository = $metricRepository;
        $this->valueRepository = $valueRepository;
    }

    /**
     * @param Section $section
     * @return Statistics
     * @example of returned data
     * [
     *      'headers' => ['metricName1', 'metricName2', ...],
     *      'values' => [
     *          '2019-08-10' => [
     *              'metricName1' => [
     *                  'averageValue' => 50.5,
     *              ],
     *              'metricName2' => [
     *                  'averageValue' => 69.1,
     *              ]
     *              ...
     *          ],
     *          '2019-08-09' => [
     *              'metricName1' => [
     *                  'averageValue' => 50.5,
     *              ],
     *              'metricName2' => [
     *                  'averageValue' => null,
     *              ],
     *              ...
     *          ],
     *          ...
     *      ]
     * ]
     */
    public function getDailySectionStatistics(Section $section): Statistics
    {
        $statistics = new Statistics();

        $metrics = $this->metricRepository->findBySectionOrderedByIndex($section);

        $minDate = null;
        $maxDate = null;

        $averageValuesPerMetric = [];

        /** @var Metric $metric */
        foreach ($metrics as $metric) {
            $statistics->headers[] = $metric->getName();

            $averageValues = $this->valueRepository->getAverageValuesPerDayByMetric($metric);

            if (count($averageValues) > 0) {
                $metricMinDate = new \DateTimeImmutable(array_key_last($averageValues));
                $metricMaxDate = new \DateTimeImmutable(array_key_first($averageValues));

                if ($minDate === null || $maxDate === null) {
                    $minDate = $metricMinDate;
                    $maxDate = $metricMaxDate;
                } else {
                    if ($minDate > $metricMinDate) {
                        $minDate = $metricMinDate;
                    }
                    if ($maxDate < $metricMaxDate) {
                        $maxDate = $metricMaxDate;
                    }
                }
            }

            $averageValuesPerMetric[$metric->getName()] = $averageValues;
        }

        if ($minDate && $maxDate) {
            $statistics->values = $this->generateStatisticValues($averageValuesPerMetric, $minDate, $maxDate);
        }

        return $statistics;
    }

    private function generateStatisticValues(array $averageValuesPerMetric, \DateTimeImmutable $minDate, \DateTimeImmutable $maxDate): ValuePerMetricPerDay
    {
        $days = $this->getDaysBetween($minDate, $maxDate);

        $valuePerMetricPerDay = new ValuePerMetricPerDay();
        $metricNames = array_keys($averageValuesPerMetric);

        foreach ($days as $day) {
            $valuePerMetric = new ValuePerMetric();

            foreach ($metricNames as $metricName) {
                $value = new Value();
                $value->averageValue = $averageValuesPerMetric[$metricName][$day] ?? null;

                $valuePerMetric->put($metricName, $value);
            }

            $valuePerMetricPerDay->put($day, $valuePerMetric);
        }

        return $valuePerMetricPerDay;
    }

    private function getDaysBetween(\DateTimeImmutable $minDate, \DateTimeImmutable $maxDate): array
    {
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($minDate, $interval, $maxDate->add($interval));
        $days = [];

        /** @var \DateTimeInterface $day */
        foreach ($period as $day) {
            $days[] = $day->format('Y-m-d');
        }

        return array_reverse($days);
    }
}
