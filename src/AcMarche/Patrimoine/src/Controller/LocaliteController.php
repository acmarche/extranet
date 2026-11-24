<?php

namespace AcMarche\Patrimoine\Controller;

use AcMarche\Patrimoine\Entity\Localite;
use AcMarche\Patrimoine\Form\LocaliteType;
use AcMarche\Patrimoine\Repository\LocaliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/localite')]
#[IsGranted('ROLE_PATRIMOINE_ADMIN')]
class LocaliteController extends AbstractController
{
    public function __construct(private readonly LocaliteRepository $localiteRepository) {}

    #[Route(path: '/', name: 'patrimoine_localite_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            '@AcMarchePatrimoine/localite/index.html.twig',
            [
                'localites' => $this->localiteRepository->findAll(),
            ],
        );
    }

    #[Route(path: '/new', name: 'patrimoine_localite_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $localite = new Localite();
        $form = $this->createForm(LocaliteType::class, $localite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->localiteRepository->persist($localite);
            $this->localiteRepository->flush();

            return $this->redirectToRoute('patrimoine_localite_show', ['id' => $localite->id]);
        }

        return $this->render(
            '@AcMarchePatrimoine/localite/new.html.twig',
            [
                'localite' => $localite,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/{id}', name: 'patrimoine_localite_show', methods: ['GET'])]
    public function show(Localite $localite): Response
    {
        return $this->render(
            '@AcMarchePatrimoine/localite/show.html.twig',
            [
                'localite' => $localite,
            ],
        );
    }

    #[Route(path: '/{id}/edit', name: 'patrimoine_localite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localite $localite): Response
    {
        $form = $this->createForm(LocaliteType::class, $localite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->localiteRepository->flush();

            return $this->redirectToRoute('patrimoine_localite_show', ['id' => $localite->id]);
        }

        return $this->render(
            '@AcMarchePatrimoine/localite/edit.html.twig',
            [
                'localite' => $localite,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/{id}', name: 'patrimoine_localite_delete', methods: ['DELETE'])]
    public function delete(Request $request, Localite $localite): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$localite->id, $request->request->get('_token'))) {
            $this->localiteRepository->remove($localite);
            $this->localiteRepository->flush();
        }

        return $this->redirectToRoute('patrimoine_localite_index');
    }
}
