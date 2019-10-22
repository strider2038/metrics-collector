<?php
/*
 * This file is part of metrics-collector.
 *
 * (c) Igor Lazarev <strider2038@yandex.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\ValueCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class PostBatchValuesAction
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    private $validator;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): ValueCollection
    {
        /** @var ValueCollection $values */
        $values = $this->serializer->deserialize($request->getContent(), ValueCollection::class, 'jsonld');

        $this->validator->validate($values);

        foreach ($values as $value) {
            $this->entityManager->persist($value);
        }

        $this->entityManager->flush();

        return $values;
    }
}
