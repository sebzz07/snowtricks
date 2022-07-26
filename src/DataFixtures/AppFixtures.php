<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public const ADMIN_USER_REFERENCE = 'admin';

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
            ->setIsVerified(1);
        $manager->persist($userAdmin);
        $manager->flush();
        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);

        for ($i = 1; $i <= 50; $i++) {
            $trick = new Trick();
            $trick->setName("snowtrick n°" . $i)
                ->setSlug("snowtrick" . $i)
                ->setDescription("snowtrick descritption n°" . $i)
                ->setPublicationStatusTrick("published")
                ->SetUser($this->getReference(AppFixtures::ADMIN_USER_REFERENCE));
            $manager->persist($trick);

            for ($j = 1; $j <= 10; $j++) {
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
