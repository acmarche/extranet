<?php

namespace AcMarche\Patrimoine\Controller;

use AcMarche\EnquetePublique\Form\ImageDropZoneType;
use AcMarche\Patrimoine\Entity\Image;
use AcMarche\Patrimoine\Entity\Patrimoine;
use AcMarche\Patrimoine\Repository\ImageRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Handler\UploadHandler;

#[IsGranted('ROLE_PATRIMOINE_ADMIN')]
class ImageController extends AbstractController
{
    public function __construct(private readonly ImageRepository $imageRepository, private readonly UploadHandler $uploadHandler) {}

    #[Route(path: '/images/{id}', name: 'patrimoine_images')]
    public function index(Request $request, Patrimoine $patrimoine): Response
    {
        $image = new Image($patrimoine);
        $form = $this->createForm(ImageDropZoneType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $data
             */
            $data = $form->getData();
            foreach ($data['file'] as $file) {
                if ($file instanceof UploadedFile) {
                    $image = new Image($patrimoine);
                    $orignalName = preg_replace(
                        '#.'.$file->guessClientExtension().'#',
                        '',
                        $file->getClientOriginalName(),
                    );
                    $fileName = $orignalName.'-'.uniqid().'.'.$file->guessClientExtension();

                    $image->mime = $file->getMimeType();
                    $image->fileName = $fileName;
                    $image->file = $file;

                    try {
                        $this->uploadHandler->upload($image, 'file');
                        $this->imageRepository->persist($image);
                        $this->imageRepository->flush();
                    } catch (Exception $exception) {
                        $this->addFlash('danger', $exception->getMessage());
                    }
                }
            }

            return $this->redirectToRoute('patrimoine_show', ['id' => $patrimoine->id]);
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_ACCEPTED : Response::HTTP_OK);

        return $this->render(
            '@AcMarchePatrimoine/image/edit.html.twig',
            [
                'patrimoine' => $patrimoine,
                'form' => $form->createView(),
            ]
            , $response,
        );
    }

    #[Route(path: '/image/{id}', name: 'patrimoine_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render(
            '@AcMarchePatrimoine/image/show.html.twig',
            [
                'image' => $image,
                'patrimoine' => $image->patrimoine,
            ],
        );
    }

    #[Route(path: '/image/{id}', name: 'patrimoine_image_delete', methods: ['DELETE'])]
    public function delete(Request $request, Image $image): RedirectResponse
    {
        $patrimoine = $image->patrimoine;
        if ($this->isCsrfTokenValid('delete'.$image->id, $request->request->get('_token'))) {
            $this->imageRepository->remove($image);
            $this->imageRepository->flush();
            $this->addFlash('success', "L'image a bien été supprimée");
        }

        return $this->redirectToRoute('patrimoine_show', ['id' => $patrimoine->id]);
    }
}
