<?php

namespace GemeenteAmsterdam\FixxxSchuldhulp\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use GemeenteAmsterdam\FixxxSchuldhulp\Entity\Team;

class TeamFixtures extends \Doctrine\Bundle\FixturesBundle\Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        foreach ([1, 2, 3] as $teamNumber) {
            $team = new Team();
            $team->setNaam("GKA Team $teamNumber");
            $team->setEmail("gka$teamNumber@example.com");

            $manager->persist($team);
        }

        $manager->flush();
    }
}