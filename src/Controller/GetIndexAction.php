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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Igor Lazarev <strider2038@yandex.ru>
 */
class GetIndexAction extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function __invoke(): Response
    {
        return $this->redirect('/statistics');
    }
}
