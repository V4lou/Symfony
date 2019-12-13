<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Faker;
use App\entity\Category;
use App\Entity\Program;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
       /* foreach (self::ACTORS as $key => $actorName) {

            $actor = new Actor();
            $actor->setName($actorName);
            $actor->addProgram($this->getReference('program_0'));
            $manager->persist($actor);
            $this->addReference('actor_' . $key, $actor);
        }*/
        $faker  =  Faker\Factory::create('en_US');

            for ($i = 1; $i <= 10; $i++) {
                $season = new Season();
                $saesonYear = $faker->year();
                $seasonDescription = $faker->text();
                $seasonNumber = $faker->numberBetween(min([1]),max([20]));
                $season->setDescription($seasonDescription);
                $season->setNumber($seasonNumber);
                $this->addReference('season_'.$i, $season);
                $season->setYear($saesonYear);
                $numberProg = rand(0,5);
               /* for ($j = 0; $j <= 5; $j++) {
                    $season->setProgram($this->getReference('program_'.$j));
                }*/
                $season->setProgram($this->getReference('program_' . $numberProg));
                $manager->persist($season);
            }


        $manager->flush();
    }
}
