<?php
/*
 * This file is part of metrics-collector.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Contracts;

use App\Entity\Metric;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
interface IndexedEntityInterface
{
    public function setOrderIndex(int $orderIndex);

    public function getOrderIndex(): int;
}
