<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Pictures;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public const ADMIN_USER_REFERENCE = 'admin';
    public const USER_USER_REFERENCE = 'user';

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $userAdmin = new User();
        $password = $this->hasher->hashPassword($userAdmin, 'admin');

        $userAdmin->setUsername('admin')
            ->setPassword($password)
            ->setEmail('sebdru.fr@gmail.com')
            ->setFullName('Sébastien Dru')
            ->setRoles(array('ROLE_ADMIN'))
            ->setIsVerified(1);
        $manager->persist($userAdmin);
        $manager->flush();

        $user = new User();
        $password2 = $this->hasher->hashPassword($user, 'user');

        $user->setUsername('user')
            ->setPassword($password2)
            ->setEmail('sebdru.fr@gmail.com')
            ->setFullName('Sébastien Dru')
            ->setRoles(array('ROLE_USER'))
            ->setIsVerified(1);
        $manager->persist($user);
        $manager->flush();

        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);
        $this->addReference(self::USER_USER_REFERENCE, $user);

        $category = new Category();
        $category->setName("Nose grab")->setSlug("nose-grab");
        $category->setName("Japan")->setSlug("japan");
        $category->setName("Seat belt")->setSlug("seat-belt");
        $category->setName("Truck driver")->setSlug("truck-driver");
        $category->setName("Rotation")->setSlug("rotation");
        $category->setName("180")->setSlug("180");
        $category->setName("360")->setSlug("360");
        $category->setName("540")->setSlug("540");
        $category->setName("720")->setSlug("720");
        $category->setName("900")->setSlug("900");
        $category->setName("1080")->setSlug("1080");
        $category->setName("90")->setSlug("90");
        $category->setName("270")->setSlug("270");
        $category->setName("450")->setSlug("450");
        $category->setName("630")->setSlug("630");
        $category->setName("810")->setSlug("810");
        $category->setName("Front flips")->setSlug("front-flips");
        $category->setName("Back flips")->setSlug("back-flips");
        $category->setName("Slide")->setSlug("slide");
        $category->setName("Nose slide")->setSlug("nose-slide");
        $category->setName("Tail slide")->setSlug("tail-slide");
        $category->setName("One foot tricks")->setSlug("one-foot-tricks");
        $manager->persist($category);

        for ($i = 1; $i <= 50; $i++) {
            $trick = new Trick();
            $trick->setName("snowtrick n°" . $i)
                ->setSlug("snowtrick" . $i)
                ->setDescription("snowtrick description n°" . $i)
                ->setPublicationStatusTrick("Published")
                ->SetUser($this->getReference(self::ADMIN_USER_REFERENCE));
            $manager->persist($trick);

            for ($j = 1; $j <= rand(1,5); $j++) {
                $picture = new Pictures();
                $picture->setPictureLink("php54645F-645BRa1aca.jpg")
                    ->setPictureName("Name of picture N°" . $j ." of trick n°" . $i)
                    ->setTrick($trick)
                    ->setUser($this->getReference(self::ADMIN_USER_REFERENCE));
                $manager->persist($picture);
                $j++;
                $picture = new Pictures();
                $picture->setPictureLink("php981F-630a00a1a44ca.jpg")
                    ->setPictureName("Name of picture N°" . $j ." of trick n°" . $i)
                    ->setTrick($trick)
                    ->setUser($this->getReference(self::ADMIN_USER_REFERENCE));
                $manager->persist($picture);
            }
            for ($j = 1; $j <= rand(1,5); $j++) {
                $video = new Video();
                $video->setVideoLink("https://www.youtube.com/embed/SQyTWk7OxSI")
                    ->setVideoName("Name of video N°" . $j ." of trick n°" . $i)
                    ->setTrick($trick)
                    ->SetUser($this->getReference(AppFixtures::ADMIN_USER_REFERENCE));
                $manager->persist($video);
            }


            for ($j = 1; $j <= rand(5,15); $j++) {
                $post = new Post();
                $post->setPostContent("Discusion about trick n°" . $i . " post N°" . $j)
                    ->setTrick($trick)
                    ->SetUser($this->getReference(AppFixtures::ADMIN_USER_REFERENCE));
                $manager->persist($post);
            }
        }
        $manager->flush();
    }
}
