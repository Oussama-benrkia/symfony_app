<?php

namespace App\DataFixtures;

use App\Entity\Listings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        for($i=0;$i<20;$i++){
            $listing=new Listings();
            $listing->setTitle($faker->sentence(3));
            $listing->setTags(json_encode($faker->randomElements(['PHP', 'Symfony', 'Docker', 'Git', 'DevOps', 'Java'], mt_rand(2, 4))));
            $listing->setCompany($faker->company());
            $listing->setEmail($faker->companyEmail());
            $listing->setWebsite($faker->url());
            $listing->setDescription($faker->paragraph(4));
            $listing->setLocation($faker->address);
            $manager->persist($listing);
              }
            $manager->flush();
    }

}
