<?php

namespace AcMarche\EnquetePublique\Enquete\MessageHandler;

use AcMarche\EnquetePublique\Enquete\Message\EnqueteDeleted;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
final readonly class EnqueteDeletedHandler
{
    private FlashBagInterface $flashBag;

    public function __construct(RequestStack $requestStack)
    {
        $this->flashBag = $requestStack->getSession()->getFlashBag();
    }

    public function __invoke(EnqueteDeleted $enqueteDeleted): void
    {
        $this->flashBag->add('success', 'L\'enquête a bien été supprimée');
    }
}
