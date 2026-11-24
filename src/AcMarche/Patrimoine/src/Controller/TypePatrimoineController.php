<?php

namespace AcMarche\Patrimoine\Controller;

use AcMarche\Patrimoine\Entity\TypePatrimoine;
use AcMarche\Patrimoine\Form\TypePatrimoineType;
use AcMarche\Patrimoine\Repository\TypePatrimoineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/type')]
#[IsGranted('ROLE_PATRIMOINE_ADMIN')]
class TypePatrimoineController extends AbstractController
{
    public function __construct(private readonly TypePatrimoineRepository $typePatrimoineRepository) {}

    #[Route(path: '/', name: 'patrimoine_type_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            '@AcMarchePatrimoine/type/index.html.twig',
            [
                'types' => $this->typePatrimoineRepository->findAll(),
            ],
        );
    }

    #[Route(path: '/new', name: 'patrimoine_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $typePatrimoine = new TypePatrimoine();
        $form = $this->createForm(TypePatrimoineType::class, $typePatrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->typePatrimoineRepository->persist($typePatrimoine);
            $this->typePatrimoineRepository->flush();

            return $this->redirectToRoute('patrimoine_type_show', ['id' => $typePatrimoine->id]);
        }

        return $this->render(
            '@AcMarchePatrimoine/type/new.html.twig',
            [
                'type' => $typePatrimoine,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/{id}', name: 'patrimoine_type_show', methods: ['GET'])]
    public function show(TypePatrimoine $typePatrimoine): Response
    {
        return $this->render(
            '@AcMarchePatrimoine/type/show.html.twig',
            [
                'type' => $typePatrimoine,
            ],
        );
    }

    #[Route(path: '/{id}/edit', name: 'patrimoine_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypePatrimoine $typePatrimoine): Response
    {
        $form = $this->createForm(TypePatrimoineType::class, $typePatrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->typePatrimoineRepository->flush();

            return $this->redirectToRoute('patrimoine_type_show', ['id' => $typePatrimoine->id]);
        }

        return $this->render(
            '@AcMarchePatrimoine/type/edit.html.twig',
            [
                'type' => $typePatrimoine,
                'form' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/{id}', name: 'patrimoine_type_delete', methods: ['DELETE'])]
    public function delete(Request $request, TypePatrimoine $typePatrimoine): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$typePatrimoine->id, $request->request->get('_token'))) {
            $this->typePatrimoineRepository->remove($typePatrimoine);
            $this->typePatrimoineRepository->flush();
        }

        return $this->redirectToRoute('patrimoine_type_index');
    }
}
