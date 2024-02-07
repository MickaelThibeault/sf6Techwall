<?php

namespace App\EventListener;

use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonneEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class PersonneListener
{

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onPersonneAdd(AddPersonneEvent $event)
    {
        $this->logger->debug('Je suis en train d écouter et la personne ajoutée est '.$event->getPersonne()->getName() );
    }

    public function onListAllsPersonnes(ListAllPersonneEvent $listAllPersonneEvent)
    {
        $this->logger->debug('Je suis en train d écouter et le nombre de personnes est '.$listAllPersonneEvent->getNbPersonnes() );

    }

    public function logKernelRequest(KernelEvent $event)
    {
        dd($event->getRequest());

    }
}