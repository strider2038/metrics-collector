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

use App\Service\SectionReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class GetStatisticsIndexAction extends AbstractController
{
    /** @var SectionReader */
    private $sectionReader;

    public function __construct(SectionReader $sectionReader)
    {
        $this->sectionReader = $sectionReader;
    }

    /**
     * @Route("/statistics", name="statistics", methods={"GET"})
     */
    public function __invoke(): Response
    {
        $section = $this->sectionReader->findFirstSection();

        if ($section === null) {
            throw new \DomainException('Not implemented');
        }

        return $this->redirect('/statistics/'.$section->getName());
    }
}
