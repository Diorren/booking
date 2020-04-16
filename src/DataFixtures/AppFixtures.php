<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        
        $users = [];
        $genres = ['male','female'];

        // UTILISATEURS
        for($i=1; $i<=10;$i++){

            $user = new User();
            $genre = $faker->randomElement($genres);
            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1,99).'.jpg';
            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;
            $hash = $this->passwordEncoder->encodePassword($user,'password');

            $description = "<p>" . join("</p><p>", $faker->paragraphs(5)) . "</p>";
            $user->setFirstname($faker->firstname)
                 ->setLastname($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription($description)
                 ->setHash($hash)
                 ->setAvatar($avatar)
                 ;
            $manager->persist($user);
            $users[] = $user;

            
        }
        
        // ANNONCES

        for($i=1; $i<=30; $i++){
        $ad = new Ad();

        $title = $faker->sentence();
        $coverImage = $faker->imageUrl(200,150);
        $description = $faker->paragraph(2);
        $content = "<p>".join("</p><p>",$faker->paragraphs(5)). "</p>";
        $user = $users[mt_rand(0,count($users)-1)];
        
        $ad->setTitle($title)
           ->setCoverImage($coverImage)
           ->setDescription($description)
           ->setContent($content)
           ->setPrice(mt_rand(30,200))
           ->setRooms(mt_rand(3,8))
           ->setAuthor($user)
           ;
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
