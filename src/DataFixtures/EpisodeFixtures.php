<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODE_NUMBER = 10;

    public function load(ObjectManager $manager): void
    {

        foreach (CategoryFixtures::CATEGORIES as $categoryKey => $categoryName) {
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 5; $j++) {
                    for ($k = 0; $k < 10; $k++) {
                        $episode = new Episode();
                        $episode->setTitle('Titre de l\'épisode');
                        $episode->setNumber($k + 1);
                        $episode->setSynopsis('Synopsis de l\'épisode');
                        $season = $this->getReference('category_' . $categoryKey . '_program_' . $i . '_season_' . $j);
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
