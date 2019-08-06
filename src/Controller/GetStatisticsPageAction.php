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
class GetStatisticsPageAction extends AbstractController
{
    /** @var SectionReader */
    private $sectionReader;

    public function __construct(SectionReader $sectionReader)
    {
        $this->sectionReader = $sectionReader;
    }

    /**
     * @Route(
     *     "/statistics/{sectionName}",
     *     name="statistics_page",
     *     methods={"GET"},
     *     requirements={
     *          "sectionName"="[0-9a-z\_]+"
     *     }
     * )
     */
    public function __invoke(string $sectionName): Response
    {
        $section = $this->sectionReader->findSection($sectionName);
        $sections = $this->sectionReader->getAllSections();

        if ($section === null) {
            $response = $this->render('statistics/not-found.html.twig', [
                'sections' => $sections,
            ]);
        } else {
            $response = $this->render('statistics/index.html.twig', [
                'currentSection' => $section,
                'sections' => $sections,
            ]);
        }

        return $response;
    }
}
