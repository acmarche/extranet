<?php


namespace AcMarche\Extranet\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="extranet_home")
     */
    public function index(): Response
    {
        return $this->render(
            '@Extranet/default/index.html.twig',
            [

            ]
        );
    }
}
