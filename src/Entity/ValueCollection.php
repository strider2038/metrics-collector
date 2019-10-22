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

use ApiPlatform\Core\Annotation\ApiResource;
use Ramsey\Collection\AbstractCollection;
use App\Controller\PostBatchValuesAction;

class ValueCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Value::class;
    }
}
