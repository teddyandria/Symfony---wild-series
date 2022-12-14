<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public const SEASON_NUMBER = 7;
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $numberOfProgram = ProgramFixtures::PROGRAM_NUMBER;
        $numberOfSeason = self::SEASON_NUMBER;

        foreach (CategoryFixtures::CATEGORIES as $categoryKey) {
            for ($i = 1; $i < $numberOfProgram; $i++) { //1 boucle pour créer 5 séries d'un coup
                for ($j = 1; $j <= $numberOfSeason; $j++) { // 5 saisons par série
                    $season = new Season();
                    $season->setNumber($j);
                    $season->setYear($faker->year());
                    $season->setDescription($faker->paragraphs(3, true));
                    $program = $this->getReference('category_' . $categoryKey . '_program_' . $i);
                    $this->addReference('category_' . $categoryKey . '_program_' . $i . '_season_' . $j, $season);
                    $season->setProgram($program);
                    $manager->persist($season);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
