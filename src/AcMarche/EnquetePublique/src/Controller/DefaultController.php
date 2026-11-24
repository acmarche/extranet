<?php

namespace AcMarche\EnquetePublique\Controller;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class DefaultController.
 */
#[IsGranted('ROLE_ENQUETE_ADMIN')]
class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'enquete_publique_home')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('enquete_index');
    }
}
