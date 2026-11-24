<?php

namespace AcMarche\EnquetePublique\Controller;

use AcMarche\EnquetePublique\Entity\CategorieWp;
use AcMarche\EnquetePublique\Form\CategorieWpType;
use AcMarche\EnquetePublique\Repository\CategorieWpRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/categoriewp')]
#[IsGranted('ROLE_ENQUETE_ADMIN')]
class CategorieWpController extends AbstractController
{
    public function __construct(private readonly CategorieWpRepository $categorieWpRepository)
    {
    }

    #[Route(path: '/', name: 'enquete_categorie_wp_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            '@EnquetePublique/categorie_wp/index.html.twig',
            [
                'categorie_wps' => $this->categorieWpRepository->findAll(),
            ]
        );
    }

    #[Route(path: '/new', name: 'enquete_categorie_wp_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $categorieWp = new CategorieWp();
        $form = $this->createForm(CategorieWpType::class, $categorieWp);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categorieWpRepository->persist($categorieWp);
            $this->categorieWpRepository->flush();

            $this->addFlash('success', 'La catégorie a bien été ajoutée');

            return $this->redirectToRoute('enquete_categorie_wp_show', ['id' => $categorieWp->getId()]);
        }

        return $this->render(
            '@EnquetePublique/categorie_wp/new.html.twig',
            [
                'categorie_wp' => $categorieWp,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'enquete_categorie_wp_show', methods: ['GET'])]
    public function show(CategorieWp $categorieWp): Response
    {
        return $this->render(
            '@EnquetePublique/categorie_wp/show.html.twig',
            [
                'categorie_wp' => $categorieWp,
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: 'enquete_categorie_wp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieWp $categorieWp): Response
    {
        $form = $this->createForm(CategorieWpType::class, $categorieWp);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categorieWpRepository->flush();
            $this->addFlash('success', 'La catégorie a bien été modifiée');

            return $this->redirectToRoute('enquete_categorie_wp_show', ['id' => $categorieWp->getId()]);
        }

        return $this->render(
            '@EnquetePublique/categorie_wp/edit.html.twig',
            [
                'categorie_wp' => $categorieWp,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'enquete_categorie_wp_delete', methods: ['DELETE'])]
    public function delete(Request $request, CategorieWp $categorieWp): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$categorieWp->getId(), $request->request->get('_token'))) {
            $this->categorieWpRepository->remove($categorieWp);
            $this->categorieWpRepository->flush();
            $this->addFlash('success', 'La catégorie a bien été supprimée');
        }

        return $this->redirectToRoute('enquete_categorie_wp_index');
    }
}
