<?php

namespace App\DataFixtures;

use App\Services\Slugify;
use Faker;
use App\entity\Category;
use App\Entity\Program;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
    const ACTORS = ['John Doe', 'Mike Joe', 'Phil Hurston', 'Andrew Marks', 'Jane McVom'];
    public function load(ObjectManager $manager)
    {

        $faker  =  Faker\Factory::create('en_US');

            for ($i = 1; $i <= 50; $i++) {
                $actor = new Actor();
                $slug = new Slugify();
                $actorName = $faker->name;
                $slug = $slug->generate($actorName);
                $actor->setName($actorName);
                $actor->setSlug($slug);
                $numberProg = rand(0,5);
                /*for ($j = 0; $j <= 5; $j++) {
                    $actor->addProgram($this->getReference('program_'.$j));
                }*/
                $actor->addProgram($this->getReference('program_'.$numberProg));
                $manager->persist($actor);
            }


        $manager->flush();
    }
}
