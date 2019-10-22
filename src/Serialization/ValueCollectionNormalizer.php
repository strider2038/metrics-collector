<?php
/*
 * This file is part of metrics-collector.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Serialization;

use App\Entity\Value;
use App\Entity\ValueCollection;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class ValueCollectionNormalizer implements NormalizerInterface, NormalizerAwareInterface, DenormalizerInterface, DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ValueCollection;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        assert($object instanceof ValueCollection);

        $data = [];

        /** @var Value $value */
        foreach ($object as $value) {
            $data[] = $this->normalizer->normalize($value, $format, $context);
        }

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $type === ValueCollection::class && $format === 'jsonld';
    }

    public function denormalize($data, $type, $format = null, array $context = []): ValueCollection
    {
        $values = new ValueCollection();

        foreach ($data as $row) {
            $value = $this->denormalizer->denormalize($row, Value::class, $format, $context);
            $values->add($value);
        }

        return $values;
    }
}
