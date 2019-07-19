<?php

namespace App\DataFixtures;

use App\Entity\Stagiaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // create 15 stagiares! Bam!
        for ($i = 0; $i < 15; $i++) {

            $stagiare = new Stagiaire();
            $stagiare->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setDateNaissance($faker->dateTimeBetween('-30 years', '-20 years'))
                ->setSexe('Homme')
                ->setVille('Strasbourg')
                ->setMail($faker->freeEmail())
                ->setTelephone($faker->phoneNumber())
                ->setAvatar('https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_960_720.png');

            $manager->persist($stagiare);
        }

        $manager->flush();
    }
}
