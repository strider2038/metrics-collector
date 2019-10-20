<?php
/*
 * This file is part of metrics-collector.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Ramsey\Collection\AbstractCollection;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class MetricCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Metric::class;
    }
}
