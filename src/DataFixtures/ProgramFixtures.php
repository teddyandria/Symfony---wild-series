<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Program;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAM_NUMBER = 3;
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $numberOfProgram = self::PROGRAM_NUMBER;
        foreach (CategoryFixtures::CATEGORIES as $categoryName) {
            for ($i = 1; $i <= $numberOfProgram; $i++) {
                $program = new Program();
                $program->setTitle($faker->sentence(3, true));
                $program->setSynopsis('Synopsis de la série');
                $slug = $this->slugger->slug($program->getTitle());
                $program->setSlug($slug);
                $this->addReference('category_' . $categoryName . '_program_' . $i, $program);
                $program->setCategory($this->getReference('category_' . $categoryName));

                $manager->persist($program);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
