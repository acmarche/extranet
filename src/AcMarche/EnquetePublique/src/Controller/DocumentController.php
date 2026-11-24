<?php

namespace AcMarche\EnquetePublique\Controller;

use AcMarche\EnquetePublique\Entity\Document;
use AcMarche\EnquetePublique\Entity\Enquete;
use AcMarche\EnquetePublique\Form\DocumentEditType;
use AcMarche\EnquetePublique\Form\DocumentType;
use AcMarche\EnquetePublique\Repository\DocumentRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/document')]
#[IsGranted('ROLE_ENQUETE_ADMIN')]
class DocumentController extends AbstractController
{
    public function __construct(private readonly DocumentRepository $documentRepository)
    {
    }

    #[Route(path: '/new/{id}', name: 'enquete_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Enquete $enquete): Response
    {
        $document = new Document($enquete);
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->documentRepository->persist($document);
            $this->documentRepository->flush();

            $this->addFlash('success', 'Le document a bien été créé');

            return $this->redirectToRoute('enquete_show', ['id' => $enquete->getId()]);
        }

        return $this->render(
            '@EnquetePublique/document/new.html.twig',
            [
                'enquete' => $enquete,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a Document entity.
     */
    #[Route(path: '/{id}', name: 'enquete_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render(
            '@EnquetePublique/document/show.html.twig',
            [
                'document' => $document,
                'enquete' => $document->getEnquete(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Document entity.
     */
    #[Route(path: '/{id}/edit', name: 'enquete_document_edit', methods: ['GET', 'POST'])]
    public function edit(Document $document, Request $request): Response
    {
        $editForm = $this->createForm(DocumentEditType::class, $document);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->documentRepository->flush();
            $this->addFlash('success', 'Le document a bien été modifié');

            return $this->redirectToRoute('enquete_document_show', ['id' => $document->getId()]);
        }

        return $this->render(
            '@EnquetePublique/document/edit.html.twig',
            [
                'document' => $document,
                'form' => $editForm->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'bottin_document_delete', methods: ['DELETE'])]
    public function delete(Request $request, Document $document): RedirectResponse
    {
        $enquete = $document->getEnquete();
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $this->documentRepository->remove($document);
            $this->documentRepository->flush();
            $this->addFlash('success', 'Le document a bien été supprimé');
        }

        return $this->redirectToRoute('enquete_show', ['id' => $enquete->getId()]);
    }
}
