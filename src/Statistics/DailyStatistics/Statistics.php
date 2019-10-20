<?php
/*
 * This file is part of metrics-collector.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Statistics\DailyStatistics;

use App\Statistics\DailyStatistics\ValuePerMetricPerDay;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class Statistics
{
    /** @var string[] */
    public $headers = [];

    /** @var ValuePerMetricPerDay */
    public $values;

    public function __construct()
    {
        $this->values = new ValuePerMetricPerDay();
    }
}
