<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        
        for($i=1; $i<=30; $i++){
        $ad = new Ad();

        $title = $faker->sentence();
        $coverImage = $faker->imageUrl(200,150);
        $description = $faker->paragraph(2);
        $content = "<p>".join("</p><p>",$faker->paragraphs(5)). "</p>";
        
        $ad->setTitle($title)
           ->setCoverImage($coverImage)
           ->setDescription($description)
           ->setContent($content)
           ->setPrice(mt_rand(30,200))
           ->setRooms(mt_rand(3,8));
        $manager->persist($ad);

        for($j=1; $j <=mt_rand(2,5);$j++){

            // On crée une nouvelle instance de l'entité image
            $image = new Image();
            $image->setUrl($faker->imageUrl())
                  ->setCaption($faker->sentence())
                  ->setAd($ad);
            // On sauvegarde
            $manager->persist($image);
        }
        }
        $manager->flush();
    }
}
