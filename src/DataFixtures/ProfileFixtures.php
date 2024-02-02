<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setRs('Facebook');
        $profile->setUrl('https://wwww.facebook.com/aymen.sellaouti');

        $profile1 = new Profile();
        $profile1->setRs('Twitter');
        $profile1->setUrl('https://wwww.twitter.com/aymensellaouti');

        $profile2 = new Profile();
        $profile2->setRs('LinkedIn');
        $profile2->setUrl('https://wwww.linkedin.com/aymen-sellaouti-b0427731/');

        $profile3 = new Profile();
        $profile3->setRs('Github');
        $profile3->setUrl('https://wwww.github.com/aymensellaouti');

        $manager->persist($profile);
        $manager->persist($profile1);
        $manager->persist($profile2);
        $manager->persist($profile3);
        $manager->flush();
    }
}
