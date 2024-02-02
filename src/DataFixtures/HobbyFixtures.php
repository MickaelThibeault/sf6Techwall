<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class HobbyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $hobbies = [
            "Yoga",
            "Cuisine",
            "Pâtisserie",
            "Photographie",
            "Blogging",
            "Lecture",
            "Apprendre une langue",
            "Construction légo",
            "Dessin",
            "Coloriage",
            "Peinture",
            "Se lancer dans le tissage de tapis",
            "Créer des vêtements ou des cosplay",
            "Fabriquer des bijoux",
            "Travailler le métal",
            "Décorer des galets",
            "Faire des puzzles avec de plus en plus de pièces",
            "Créer des miniatures (maisons, voitures, trains, bateaux, etc...",
            "Améliorer son espace de vie",
            "Apprendre à jongler",
            "Faire partie d'un club de lecture",
            "Apprendre la programmation informatique",
            "En apprendre plus sur le survivalisme",
            "Créer une chaîne Youtube",
            "Jouer au fléchettes",
            "Apprendre à chanter"
        ];

        for ($i=0; $i<count($hobbies); $i++) {
            $hobby = new Hobby();
            $hobby->setDesignation($hobbies[$i]);
            $manager->persist($hobby);
        }

        $manager->flush();
    }
}
