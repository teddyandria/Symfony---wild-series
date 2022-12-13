<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODE_NUMBER = 10;

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();

        foreach (CategoryFixtures::CATEGORIES as $categoryName) {
            for ($i = 1; $i < 3; $i++) {
                for ($j = 1; $j < 5; $j++) {
                    for ($k = 1; $k < 10; $k++) {
                        $episode = new Episode();
                        $episode->setTitle($faker->text());
                        $episode->setNumber($k + 1);
                        $episode->setSynopsis($faker->paragraphs(1, true));
                        $season = $this->getReference('category_' . $categoryName . '_program_' . $i . '_season_' . $j);
                        $episode->setSeason($season);
                        $manager->persist($episode);
                    }
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
