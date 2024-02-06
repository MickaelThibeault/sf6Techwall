<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Service\Helpers;
use App\Service\MailerService;
use App\Service\PdfService;
use App\Service\UploaderService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/personne', name: 'app_personne')]
#[IsGranted("ROLE_USER")]
class PersonneController extends AbstractController
{

    public function __construct(private LoggerInterface $logger, private Helpers $helpers)
    {

    }

    #[Route('/', name: '.list')]
    public function index(ManagerRegistry $doctrine) : Response {

        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig', [
            'personnes'=>$personnes
        ]);
    }

    #[Route('/pdf/{id}', name: '.pdf')]
    public function generatePdfPersonne(Personne $personne = null, PdfService $pdfService) {

        $html = $this->render('personne/detail.html.twig',
            [
                'personne'=>$personne
            ]);
        $pdfService->showPdfFile($html);
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
    #[IsGranted("ROLE_USER")]
    public function indexAll(ManagerRegistry $doctrine, $page, $nbre) : Response {

//        echo($this->helpers->sayCc());

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
    public function addPersonne(Request $request, ManagerRegistry $doctrine): Response
    {
        $personne = new Personne();

        $form = $this->createForm(PersonneType::class, $personne);
//        $form->remove('createdAt');
//        $form->remove('updateAt');

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();

            $this->addFlash('success', $personne->getName().' a été ajouté avec succès');

            return $this->redirectToRoute('app_personne.list');
        } else {
            return $this->render('personne/add.html.twig', [
                'form'=>$form->createView()
            ]);
        }
    }

    #[Route('/edit/{id?0}', name: '.edit')]
//    #[IsGranted("ROLE_ADMIN")]
    public function editPersonne(
            UploaderService $uploader,
            Personne $personne = null,
            Request $request,
            ManagerRegistry $doctrine,
            MailerService $mailer
        ): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $new = false;
        if (!$personne) {
            $new = true;
            $personne = new Personne();
        }

        $form = $this->createForm(PersonneType::class, $personne);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();

            if ($photo) {
                $directory = $this->getParameter('personne_directory');

                $personne->setImage($uploader->uploadFile($photo, $directory));
            }

            if ($new) {
                $message = ' a été ajouté avec succès';
                $personne->setCreatedBy($this->getUser());
            }
            else
                $message = ' a été mis à jour avec succès';

            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();



//            $emailMessage = $personne->getFirstname().' '.$personne->getName().$message;

            $this->addFlash('success', $personne->getName().$message);
//            $mailer->sendEmail(content: $emailMessage);

            return $this->redirectToRoute('app_personne.list');
        } else {
            return $this->render('personne/add.html.twig', [
                'form'=>$form->createView()
            ]);
        }
    }

    #[Route('/delete/{id}', name: '.delete')]
    #[IsGranted("ROLE_ADMIN")]
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
