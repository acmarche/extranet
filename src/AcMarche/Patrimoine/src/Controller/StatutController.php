<?php

namespace AcMarche\Patrimoine\Controller;

use AcMarche\Patrimoine\Entity\Statut;
use AcMarche\Patrimoine\Form\StatutType;
use AcMarche\Patrimoine\Repository\StatutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/statut')]
#[IsGranted('ROLE_PATRIMOINE_ADMIN')]
class StatutController extends AbstractController
{
    public function __construct(private readonly StatutRepository $statutRepository) {}

    #[Route(path: '/', name: 'patrimoine_statut_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            '@AcMarchePatrimoine/statut/index.html.twig',
            [
                'statuts' => $this->statutRepository->findAll(),
            ],
        );
    }

    #[Route(path: '/new', name: 'patrimoine_statut_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->statutRepository->persist($statut);
            $this->statutRepository->flush();

            return $this->redirectToRoute('patrimoine_statut_show', ['id' => $statut->id]);
        }

        return $this->render(
            '@AcMarchePatrimoine/statut/new.html.twig',
            [
                'statut' => $statut,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/{id}', name: 'patrimoine_statut_show', methods: ['GET'])]
    public function show(Statut $statut): Response
    {
        return $this->render(
            '@AcMarchePatrimoine/statut/show.html.twig',
            [
                'statut' => $statut,
            ],
        );
    }

    #[Route(path: '/{id}/edit', name: 'patrimoine_statut_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statut $statut): Response
    {
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->statutRepository->flush();

            return $this->redirectToRoute('patrimoine_statut_show', ['id' => $statut->id]);
        }

        return $this->render(
            '@AcMarchePatrimoine/statut/edit.html.twig',
            [
                'statut' => $statut,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/{id}', name: 'patrimoine_statut_delete', methods: ['DELETE'])]
    public function delete(Request $request, Statut $statut): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$statut->id, $request->request->get('_token'))) {
            $this->statutRepository->remove($statut);
            $this->statutRepository->flush();
        }

        return $this->redirectToRoute('patrimoine_statut_index');
    }
}
