<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Actor;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{

    public const NUMBER_OF_ACTOR = 10;
    public const PROGRAM_BY_ACTOR = 3;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $programByActor = self::PROGRAM_BY_ACTOR;

        for ($i = 1; $i <= self::NUMBER_OF_ACTOR; $i++) {

            $actor = new Actor();
            $actorName = $faker->name();
            $actor->setName($actorName);
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);

            for ($j = 0; $j < $programByActor; $j++) {
                $program = $this->getReference('category_' . CategoryFixtures::CATEGORIES[$faker->numberBetween(0, 4)] . '_program_' . $faker->numberBetween(1, 3));
                $actor->addProgram($program);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class
        ];
    }
}
