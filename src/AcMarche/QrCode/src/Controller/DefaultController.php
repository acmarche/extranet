<?php

namespace AcMarche\QrCode\Controller;

use AcMarche\QrCode\Entity\QrCode;
use AcMarche\QrCode\QrBuilder;
use AcMarche\QrCode\Repository\QrCodeRepository;
use Imagine\Exception\NotSupportedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/qrcode')]
class DefaultController extends AbstractController
{
    public function __construct(
        private readonly QrCodeRepository $qrCodeRepository,
        private readonly QrBuilder $qrBuilder,
    ) {
    }

    #[Route(path: '/', name: 'qrcode_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) {
            $codes = $this->qrCodeRepository->findByUser($user->getUserIdentifier());
        } else {
            $codes = [];
        }

        return $this->render(
            '@AcMarcheQrCode/default/index.html.twig',
            [
                'codes' => $codes,
            ],
        );
    }

    #[Route(path: '/select/type', name: 'qrcode_select_type')]
    public function select(): Response
    {
        $user = $this->getUser();

        return $this->render(
            '@AcMarcheQrCode/default/select_type.html.twig',
            [
                'uuid' => $user?->uuid,
            ],
        );
    }

    #[Route(path: '/new/{type}/{uuid}', name: 'qrcode_new')]
    public function new(Request $request, string $type, ?string $uuid = null): Response
    {
        $qrCode = null;
        if ($uuid) {
            $qrCode = $this->qrCodeRepository->findByUuid($uuid);
        }
        if (!$qrCode) {
            $qrCode = new QrCode();
        }

        $form = $this->qrBuilder->buildForm($type, $qrCode);

        $form->handleRequest($request);

        //Generates a QrCode with an image centered in the middle.
        //QrCode::format('png')->merge('path-to-image.png')->generate();

        //Generates a QrCode with an image centered in the middle.  The inserted image takes up 30% of the QrCode.
        //QrCode::format('png')->merge('path-to-image.png', .3)->generate();

        //Generates a QrCode with an image centered in the middle.  The inserted image takes up 30% of the QrCode.
        //QrCode::format('png')->merge('http://www.google.com/someimage.png', .3, true)->generate();
        $qrString = '';
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var QrCode $data */
            $data = $form->getData();

            try {
                $qr = $this->qrBuilder->generateDataType($type, $qrCode);
                $qrString = (string)$qr;
                $imageName = $this->qrBuilder->generate((string)$qr, $data->format);
                $qrCode->filePath = $this->qrBuilder->imageWebPath($imageName);

                if ($data->name) {
                    $user = $this->getUser();
                    if ($user) {
                        $qrCode->username = $user->getUserIdentifier();

                        if (!$qrCode->id) {
                            $this->qrCodeRepository->persist($qrCode);
                        }
                        $this->qrCodeRepository->flush();
                    }

                    return $this->redirectToRoute('qrcode_new', ['uuid' => $qrCode->uuid]);
                }
            } catch (\Exception|NotSupportedException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_ACCEPTED : Response::HTTP_OK);

        return $this->render(
            '@AcMarcheQrCode/default/new.html.twig',
            [
                'form' => $form,
                'type' => $type,
                'imagePath' => $qrCode->filePath,
                'qrString' => $qrString,
            ],
            $response,
        );
    }

    #[Route(path: '/show/{uuid}', name: 'qrcode_show')]
    public function show(QrCode $qrCode): Response
    {
        return $this->render(
            '@AcMarcheQrCode/default/show.html.twig',
            [
                'qrcode' => $qrCode,
                'filePath' => $qrCode->filePath,
            ],
        );
    }
}
