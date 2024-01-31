<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TabController extends AbstractController
{
    #[Route('/tab/{nb<\d+>?5}', name: 'app_tab')]
    public function index($nb): Response
    {
        $notes = [];
        for ($i = 0; $i < $nb; $i++) {
            $notes[$i] = rand (0,20);
        }
        return $this->render('tab/index.html.twig', [
            'notes' => $notes
        ]);
    }

    #[Route('/tab/users', name: 'app_tab.users')]
    public function users(): Response
    {
        $users = [
            [
                'firstName'=>'Mickaël',
                'name'=>'Thibeault',
                'age'=>52
            ],
            [
                'firstName'=>'Karine',
                'name'=>'Diallo',
                'age'=>51
            ],
            [
                'firstName'=>'Vanille',
                'name'=>'Miaou',
                'age'=>2
            ]
        ];
        return $this->render('tab/users.html.twig', [
            'users'=>$users
        ]);
    }

}
