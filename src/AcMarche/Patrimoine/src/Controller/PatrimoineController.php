<?php

namespace AcMarche\Patrimoine\Controller;

use AcMarche\Patrimoine\Entity\Patrimoine;
use AcMarche\Patrimoine\Form\PatrimoineType;
use AcMarche\Patrimoine\Form\SearchPatrimoineType;
use AcMarche\Patrimoine\Repository\PatrimoineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/patrimoine')]
#[IsGranted('ROLE_PATRIMOINE_ADMIN')]
class PatrimoineController extends AbstractController
{
    public function __construct(private readonly PatrimoineRepository $patrimoineRepository) {}

    #[Route(path: '/', name: 'patrimoine_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchPatrimoineType::class);
        $form->handleRequest($request);

        $patrimoines = [];
        $search = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $patrimoines = $this->patrimoineRepository->search(
                $data['nom'],
                $data['localite'],
                $data['typePatrimoine'],
                $data['statut'],
            );
            $search = true;
        }

        return $this->render(
            '@AcMarchePatrimoine/patrimoine/index.html.twig',
            [
                'patrimoines' => $patrimoines,
                'form' => $form->createView(),
                'search' => $search,
            ],
        );
    }

    #[Route(path: '/new', name: 'patrimoine_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $patrimoine = new Patrimoine();
        $form = $this->createForm(PatrimoineType::class, $patrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->patrimoineRepository->persist($patrimoine);
            $this->patrimoineRepository->flush();

            return $this->redirectToRoute('patrimoine_show', ['id' => $patrimoine->id]);
        }

        return $this->render(
            '@AcMarchePatrimoine/patrimoine/new.html.twig',
            [
                'patrimoine' => $patrimoine,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/{id}', name: 'patrimoine_show', methods: ['GET'])]
    public function show(Patrimoine $patrimoine): Response
    {
        return $this->render(
            '@AcMarchePatrimoine/patrimoine/show.html.twig',
            [
                'patrimoine' => $patrimoine,
            ],
        );
    }

    #[Route(path: '/{id}/edit', name: 'patrimoine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Patrimoine $patrimoine): Response
    {
        $form = $this->createForm(PatrimoineType::class, $patrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->patrimoineRepository->flush();

            return $this->redirectToRoute('patrimoine_show', ['id' => $patrimoine->id]);
        }

        return $this->render(
            '@AcMarchePatrimoine/patrimoine/edit.html.twig',
            [
                'patrimoine' => $patrimoine,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/{id}', name: 'patrimoine_delete', methods: ['DELETE'])]
    public function delete(Request $request, Patrimoine $patrimoine): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$patrimoine->id, $request->request->get('_token'))) {
            $this->patrimoineRepository->remove($patrimoine);
            $this->patrimoineRepository->flush();
        }

        return $this->redirectToRoute('patrimoine_index');
    }
}
