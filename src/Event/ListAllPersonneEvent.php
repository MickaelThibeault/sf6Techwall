<?php

namespace App\Event;

use App\Entity\Personne;
use Symfony\Contracts\EventDispatcher\Event;

class ListAllPersonneEvent extends Event
{
    const LIST_ALLS_PERSONNE_EVENT = 'personne.list_alls';

    public function __construct(private int $nbPersonnes)
    {
    }

    /**
     * @return Personne
     */
    public function getNbPersonnes(): int
    {
        return $this->nbPersonnes;
    }
}