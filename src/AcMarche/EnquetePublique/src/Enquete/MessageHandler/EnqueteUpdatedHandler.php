<?php

namespace AcMarche\EnquetePublique\Enquete\MessageHandler;

use AcMarche\EnquetePublique\Enquete\Message\EnqueteUpdated;
use AcMarche\EnquetePublique\Location\LocationUpdater;
use AcMarche\EnquetePublique\Repository\EnqueteRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class EnqueteUpdatedHandler
{
    private readonly FlashBagInterface $flashBag;

    public function __construct(
        private readonly EnqueteRepository $enqueteRepository,
        private readonly LocationUpdater $locationUpdater,
        RequestStack $requestStack
    ) {
        $this->flashBag = $requestStack->getSession()->getFlashBag();
    }

    public function __invoke(EnqueteUpdated $enqueteUpdated): void
    {
        $this->flashBag->add('success', 'L\'enquête a bien été modifiée');

        $enquete = $this->enqueteRepository->find($enqueteUpdated->getEnqueteId());
        $oldRue = $enqueteUpdated->getOldRue();

        if ($oldRue !== $enquete->getRue()) {
            try {
                $this->locationUpdater->convertAddressToCoordinates($enquete);
                $this->enqueteRepository->flush();
            } catch (Exception $e) {
                $this->flashBag->add(
                    'danger',
                    $e->getMessage()
                );
            }
        }
    }
}
