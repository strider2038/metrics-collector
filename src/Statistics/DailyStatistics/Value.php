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

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class Value
{
    /** @var string */
    public $averageValue = '';

    public function toPlainValues(): array
    {
        return [
            'averageValue' => $this->averageValue,
        ];
    }
}
