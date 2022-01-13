<?php

namespace AcMarche\Extranet\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'extranet_home')]
    public function index(): Response
    {
        return $this->render(
            '@Extranet/default/index.html.twig',
            [
            ]
        );
    }
}
