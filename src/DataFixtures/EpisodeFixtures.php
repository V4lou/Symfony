<?php

namespace App\DataFixtures;

use Faker;
use App\entity\Category;
use App\Entity\Program;
use App\Entity\Episode;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [SeasonFixtures::class];
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

            for ($i = 1; $i <= 20; $i++) {
                $episode = new Episode();
                $episodeTitle = $faker->word();
                $episodeNumber = $faker->biasedNumberBetween();
                $episodeSynopsis = $faker->text;
                $episode->setTitle($episodeTitle);
                $episode->setNumber($episodeNumber);
                $episode->setSynopsis($episodeSynopsis);

                for ($j = 1; $j <= 10; $j++) {
                    $numberSeason = rand(1,10);
                    //$episode->setSeason($this->getReference('season_'.$j));
                    $episode->setSeason($this->getReference('season_'.$numberSeason));
                }
               // $episode->setSeason($this->getReference('season_'.$numberSeason));
                $manager->persist($episode);
            }


        $manager->flush();
    }
}
