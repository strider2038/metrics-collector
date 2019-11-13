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

use App\Entity\Section;
use App\Entity\SectionCollection;
use App\Repository\SectionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class SectionReader
{
    /** @var SectionRepository */
    private $sectionRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->sectionRepository = $entityManager->getRepository(Section::class);
    }

    public function findFirstSection(): ?Section
    {
        return $this->sectionRepository->findFirstSection();
    }

    public function findSection(string $name): ?Section
    {
        return $this->sectionRepository->findOneBy(['name' => $name]);
    }

    public function getAllSections(): SectionCollection
    {
        return $this->sectionRepository->findAllOrderedByIndex();
    }
}
