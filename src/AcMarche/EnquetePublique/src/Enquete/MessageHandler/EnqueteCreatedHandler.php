<?php

namespace AcMarche\EnquetePublique\Enquete\MessageHandler;

use AcMarche\EnquetePublique\Enquete\Message\EnqueteCreated;
use AcMarche\EnquetePublique\Entity\Enquete;
use AcMarche\EnquetePublique\Location\LocationUpdater;
use AcMarche\EnquetePublique\Repository\EnqueteRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class EnqueteCreatedHandler
{
    private readonly FlashBagInterface $flashBag;

    public function __construct(
        private readonly EnqueteRepository $enqueteRepository,
        private readonly LocationUpdater $locationUpdater,
        RequestStack $requestStack
    ) {
        $this->flashBag = $requestStack->getSession()->getFlashBag();
    }

    public function __invoke(EnqueteCreated $enqueteCreated): void
    {
        $enquete = $this->enqueteRepository->find($enqueteCreated->getEnqueteId());
        $this->flashBag->add('success', 'L\'enquête a bien été ajoutée');
        $this->setLocation($enquete);
        $this->enqueteRepository->flush();
    }

    private function setLocation(Enquete $enquete): void
    {
        try {
            $this->locationUpdater->convertAddressToCoordinates($enquete);
        } catch (Exception $exception) {
            $this->flashBag->add(
                'danger',
                $exception->getMessage()
            );
        }
    }

    /**
     * @inheritDoc
     */
    public static function getHandledMessages(): iterable
    {
        // handle this message on __invoke
        yield EnqueteCreated::class;

        // also handle this message on handleOtherSmsNotification
        yield EnqueteCreated::class => [
            //  'method' => 'handleElastic',
            //'priority' => 0,
            //'bus' => 'messenger.bus.default',
        ];
    }
}
