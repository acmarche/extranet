<?php

namespace AcMarche\Extranet\Controller;

use AcMarche\Extranet\Entity\User;
use AcMarche\Extranet\Repository\UserRepository;
use AcMarche\Taxe\Form\UserEditType;
use AcMarche\Taxe\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user')]
#[IsGranted('ROLE_TAXE_ADMIN')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly ManagerRegistry $managerRegistry
    ) {
    }

    /**
     * Lists all Utilisateur entities.
     */
    #[Route(path: '/', name: 'extranet_user', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userRepository->findBy([], ['nom' => 'ASC']);

        return $this->render(
            '@Extranet/user/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    #[Route(path: '/new', name: 'extranet_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, $form->getData()->getPlainPassword())
            );
            $this->userRepository->insert($user);

            $this->addFlash('success', "L'utilisateur a bien été ajouté");

            return $this->redirectToRoute('extranet_user');
        }

        return $this->render(
            '@Extranet/user/new.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'extranet_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render(
            '@Extranet/user/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: 'extranet_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $editForm = $this->createForm(UserEditType::class, $user);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->userRepository->flush();
            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('extranet_user');
        }

        return $this->render(
            '@Extranet/user/edit.html.twig',
            [
                'user' => $user,
                'form' => $editForm->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'extranet_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a été supprimé');
        }

        return $this->redirectToRoute('extranet_user');
    }
}
