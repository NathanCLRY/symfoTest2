<?php

namespace App\DataFixtures;

use Faker\Generator;
use Faker\Factory;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <=15 ; $i++) { 
            $client = new Client();
            $client->setNom($this->faker->name(1))
            ->setPrenom($this->faker->name(1))
            ->setEmail($this->faker->email());
            $manager->persist($client);
        }

        $manager->flush();
    }
}
