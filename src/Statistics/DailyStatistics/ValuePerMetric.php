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

use Ramsey\Collection\Map\AbstractTypedMap;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class ValuePerMetric extends AbstractTypedMap
{
    public function getKeyType(): string
    {
        return 'string';
    }

    public function getValueType(): string
    {
        return Value::class;
    }

    public function toPlainValues(): array
    {
        $values = [];

        /**
         * @var string $key
         * @var Value $item
         */
        foreach ($this->data as $key => $item) {
            $values[$key] = $item->toPlainValues();
        }

        return $values;
    }
}
