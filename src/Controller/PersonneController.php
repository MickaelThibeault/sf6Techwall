<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    #[Route('/{id<\d+>}', name: '.detail')]
    public function detail(Personne $personne = null) : Response {

//        $repository = $doctrine->getRepository(Personne::class);
//        $personne = $repository->find($id);

        if (!$personne) {
            $this->addFlash('error', 'La personne n\'existe pas');
            return $this->redirectToRoute('app_personne.list');
        } else {
            return $this->render('personne/detail.html.twig', [
                'personne'=>$personne
            ]);
        }

    }

    #[Route('/alls/{page?1}/{nbre?12}', name: '.list.alls')]
    public function indexAll(ManagerRegistry $doctrine, $page, $nbre) : Response {

        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonnes = $repository->count([]);
        $personnes = $repository->findBy([], [],$nbre,($page-1)*$nbre);
        $nbrePages = ceil($nbPersonnes/$nbre);
        return $this->render('personne/index.html.twig', [
            'personnes'=>$personnes,
            'isPaginated'=>true,
            'nbrePages'=>$nbrePages,
            'page'=>$page,
            'nbre'=>$nbre
        ]);
    }

    #[Route('/alls/age/{ageMin}/{ageMax}', name: '.list.alls.age')]
    public function personnesByAge(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response {

        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonneByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/index.html.twig', [
            'personnes'=>$personnes
        ]);
    }

    #[Route('/stats/age/{ageMin}/{ageMax}', name: '.stats.age')]
    public function statsPersonnesByAge(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response {

        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statsPersonnesByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/stats.html.twig', [
            'stats'=> $stats[0],
            'ageMin'=> $ageMin,
            'ageMax'=> $ageMax
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

//        $personne2 = new Personne();
//        $personne2->setFirstname('Skander');
//        $personne2->setName('Sellaouti');
//        $personne2->setAge(3);

        $entityManager->persist($personne);
//        $entityManager->persist($personne2);
        $entityManager->flush();

        return $this->render('personne/detail.html.twig', [
            'personne'=>$personne
        ]);
    }

    #[Route('/delete/{id}', name: '.delete')]
    public function deletePersonne(Personne $personne = null, ManagerRegistry $doctrine) : RedirectResponse
    {
        if ($personne) {
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();

            $this->addFlash('success', 'La personne a été supprimée avec succès');
        } else {
            $this->addFlash('error', 'La personne est inexistante ');
        }
        return $this->redirectToRoute('app_personne.list.alls');
    }

    #[Route('/update/{id}/{name}/{firstname}/{age}', name: '.update')]
    public function updatePersonne(Personne $personne = null, $name, $firstname, $age, ManagerRegistry $doctrine) : RedirectResponse
    {
        if ($personne) {
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);

            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();

            $this->addFlash('success', 'La personne a été mis à jour avec succès');
        } else {
            $this->addFlash('error', 'La personne est inexistante ');
        }
        return $this->redirectToRoute('app_personne.list.alls');
    }


}
