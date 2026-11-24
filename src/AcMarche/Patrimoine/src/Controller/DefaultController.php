<?php

namespace AcMarche\Patrimoine\Controller;

use AcMarche\Patrimoine\Repository\PatrimoineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DefaultController extends AbstractController
{
    public function __construct(private readonly PatrimoineRepository $patrimoineRepository) {}

    #[IsGranted('ROLE_PATRIMOINE_ADMIN')]
    #[Route(path: '/', name: 'patrimoine_home')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('patrimoine_index');
    }

    #[Route(path: '/api', name: 'patrimoine_api')]
    public function api(): JsonResponse
    {
        $data = [];
        foreach ($this->patrimoineRepository->findAllSorted() as $patrimoine) {
            $data[] = [
                'id' => $patrimoine->id,
                'nom' => $patrimoine->nom,
                'rue' => $patrimoine->rue,
                'numero' => $patrimoine->numero,
                'code_postal' => $patrimoine->code_postal,
                'localite' => $patrimoine->localite,
                'latitude' => $patrimoine->latitude,
                'longitude' => $patrimoine->longitude,
                'descriptif' => $patrimoine->descriptif,
                'type' => $patrimoine->typePatrimoine,
                'statut' => $patrimoine->getStatutTxt(),
                'geopoint' => $patrimoine->getGeopoint(),
                'images' => $patrimoine->images,
                'photo' => $patrimoine->photo,
            ];
        }

        return $this->json($data);
    }
}
