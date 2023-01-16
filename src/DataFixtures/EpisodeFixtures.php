<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use App\DataFixtures\SeasonFixtures;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODE_NUMBER = 10;
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

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
                        $episode->setDuration($faker->numberBetween(40, 60));
                        $slug = $this->slugger->slug($episode->getTitle());
                        $episode->setSlug($slug);
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
