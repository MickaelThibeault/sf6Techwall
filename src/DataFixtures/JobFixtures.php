<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class JobFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jobs = [
            "Data scientist",
            "Statisticien",
            "Analyste cyber-sécurité",
            'Médecin ORL',
            "Echographiste",
            "Mathématicien",
            "Ingénieur logiciel",
            "Analyste informatique",
            "Pathologiste du discours / langage",
            "Actuaire",
            "Ergothérapeute",
            "Directeur des ressources humaines",
            "Hygièniste dentaire"
        ];

        for ($i=0; $i<count($jobs); $i++) {
            $job = new Job();
            $job->setDesignation($jobs[$i]);
            $manager->persist($job);
        }

        $manager->flush();
    }
}
