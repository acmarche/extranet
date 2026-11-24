<?php

namespace AcMarche\Patrimoine\Controller;

use AcMarche\Patrimoine\Entity\Patrimoine;
use AcMarche\Patrimoine\Form\LocalisationType;
use AcMarche\Patrimoine\Repository\PatrimoineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/localisation')]
class LocalisationController extends AbstractController
{
    public function __construct(private readonly PatrimoineRepository $patrimoineRepository) {}

    #[IsGranted('ROLE_PATRIMOINE_ADMIN')]
    #[Route(path: '/{id}', name: 'patrimoine_localisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Patrimoine $patrimoine): Response
    {
        $form = $this->createForm(LocalisationType::class, $patrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->patrimoineRepository->flush();

            $this->addFlash('success', 'La localisation a bien été modifiée');

            return $this->redirectToRoute('patrimoine_show', ['id' => $patrimoine->id]);
        }

        return $this->render(
            '@Patrimoine/localisation/edit.html.twig',
            [
                'patrimoine' => $patrimoine,
                'form' => $form->createView(),
            ],
        );
    }
}
