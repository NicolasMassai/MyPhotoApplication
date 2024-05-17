<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Tag;
use App\Entity\Photo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        $tags=[];

        for($i=0; $i<90; $i++){
            $tag = new Tag();

            $tag->setName($faker->word());
            $manager->persist($tag);

            $tags[] = $tag;

            
        }

            for($j=0; $j<60; $j++){
                $photo = new Photo();

                $photo->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph())
                ->setPrice($faker->randomFloat(2, 10, 300))
                ->setUrl($faker->imageUrl(640, 480, 'animals', true))
                ->setMetaInfo([])
                ->setSlug($faker->unique()->word);
               
                $nbTags = random_int(1,5);
                for($k=0;$k < $nbTags;$k++) {
                    shuffle($tags);
                    $photo->addTag($tags[0]);
                }
                

                $createdAt = $faker->dateTimeBetween('-1 week', 'now');
                $photo->setCreatedAt(\DateTimeImmutable::createFromMutable($createdAt));

                
                $manager->persist($photo);


    }
    $manager->flush();

}
}