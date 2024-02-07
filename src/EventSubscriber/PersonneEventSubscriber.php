<?php

namespace App\EventSubscriber;

use App\Entity\Personne;
use App\Event\AddPersonneEvent;
use App\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Mailer;

class PersonneEventSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private MailerService $mailer
        )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AddPersonneEvent::ADD_PERSONNE_EVENT=>['onAddPersonneEvent', 3000]
        ];
    }

    public function onAddPersonneEvent(AddPersonneEvent $event): void
    {
        $personne = $event->getPersonne();
        $message = '';
        $emailMessage = $personne->getFirstname().' '.$personne->getName().$message;

        $this->mailer->sendEmail(content: $emailMessage, subject: 'Mail send from EventSubscriber');
    }

}