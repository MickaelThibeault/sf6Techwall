<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personne', name: 'app_personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: '.list')]
    public function index(ManagerRegistry $doctrine) : Response {

        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig', [
            'personnes'=>$personnes
        ]);
    }

    #[Route('/add', name: '.add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $personne = new Personne();
        $personne->setFirstname('Aymen');
        $personne->setName('Sellaouti');
        $personne->setAge(39);

        $personne2 = new Personne();
        $personne2->setFirstname('Skander');
        $personne2->setName('Sellaouti');
        $personne2->setAge(3);

        $entityManager->persist($personne);
        $entityManager->persist($personne2);
        $entityManager->flush();

        return $this->render('personne/detail.html.twig', [
            'personne'=>$personne
        ]);
    }
}
