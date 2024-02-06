<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class Helpers
{

    public function __construct(private LoggerInterface $logger, private Security $security)
    {
    }

    public function sayCc() : string
    {
        $this->logger->info("Je dis coucou !");
        return 'coucou !';
    }

    public function getUser(): \Symfony\Component\Security\Core\User\UserInterface|null
    {
        if ($this->security->isGranted("ROLE_ADMIN")) {
            return $this->security->getUser();
        }
        return null;
    }
}