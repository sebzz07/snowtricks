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


    private static array $categoryNameArray = [
        "Nose grab",
        "Japan",
        "Seat belt",
        "Truck driver",
        "Rotation",
        "180",
        "360",
        "540",
        "720",
        "900",
        "1080",
        "90",
        "270",
        "450",
        "630",
        "810",
        "Front flips",
        "Back flips",
        "Slide",
        "Nose slide",
        "Tail slide",
        "One foot tricks"
    ];

    private static array $categorySlugArray = [
        "nose-grab",
        "japan",
        "seat-belt",
        "truck-driver",
        "rotation",
        "180",
        "360",
        "540",
        "720",
        "900",
        "1080",
        "90",
        "270",
        "450",
        "630",
        "810",
        "front-flips",
        "back-flips",
        "slide",
        "nose-slide",
        "tail-slide",
        "one-foot-tricks"
    ];

    private static array $trickNameArray = [
        "Ollie",
        "Nollie",
        "Melon",
        "Indy",
        "Nose Grab",
        "50-50",
        "Tail Press",
        "Nose Press",
        "Frontside 180",
        "Backside 180",
        "Butter",
        "Tripod",
        "Tail Grab",
        "Boardslide",
        "Back Flip",
        "Frontside 360",
        "Backside 360",
        "Front Roll",
        "Front Flip",
        "Frontside 360+",
        "Backside 360+",
    ];

    private static array $trickSlugArray = [
        "ollie",
        "nollie",
        "melon",
        "indy",
        "nose-grab",
        "50-50",
        "tail-press",
        "nose-press",
        "frontside-180",
        "backside-180",
        "butter",
        "tripod",
        "tail-grab",
        "boardslide",
        "back-flip",
        "frontside-240",
        "backside-360",
        "front-roll",
        "front-flip",
        "frontside-360",
        "backside-290",

    ];

    private static array $trickDescriptionArray = [
        "An Ollie is probably the first snowboard trick you’ll learn. It’s your introduction to snowboard jumps. To perform an Ollie, you should shift your body weight to your back leg. Jump, making sure to lead with your front leg. Lift your back leg in line with your front.",
        "The Nollie is basically the opposite of an Ollie. When you jump, lead with your back leg. Then, lift your front leg to bring your feet in line with each other. You’ll probably find that you can catch a few inches after just a few tries.",
        "When you catch some snowboarding air, reach down and grab the heel side of the board between your feet. Congratulations, you’ve done your first Melon!",
        "You can perform an Indy by doing an Ollie off of a jump and reaching down to grab your board’s toe edge. Let go and reposition yourself for a smooth landing.",
        "If you can do a Melon and Indy, you’re ready for a nose grab. While in the air, reach down to grab the front of your board. Straighten your body and prepare for an easy landing.",
        "The 50-50 introduces you to snowboard slide tricks. When you approach a rail or box, jump to land on it and ride it until you come off at the other end. Start with short rails until you build the balance you need to ride longer ones.",
        "Practice the tail press on a flat surface where you feel comfortable. Get a little speed going before you lean backward to shift your weight to your back leg. You can lift your front leg to emphasize the bend in your snowboard.",
        "The nose press works just like the tail press. Instead of leaning backward, you shift your weight forward. It can feel a little more intimidating at first, but it requires the same skill. The hardest part is overcoming anxiety to feel comfortable on your snowboard.",
        "Ready to rotate your snowboard? With a frontside 180, you rotate your body so that your heels lead. For example, if you jump while riding downhill with your left foot forward, you would rotate in a counter counterclockwise motion for a frontside 180. Stop when your rear leg becomes your leading leg.",
        "A backside 180 is the opposite of a frontside 180. Rotate so that your toes lead. When going downhill with your left leg forward, you would rotate in a clockwise motion until your right leg becomes your leading leg.",
        "The butter takes a little more core strength than the frontside 180 and backside 180. Instead of bringing your back leg forward during a jump, you do it while maintaining contact with the snow. The snow creates a little more friction, so prepare to put some muscle into it.",
        "The tripod is a fun intermediate trick to learn. To perform one, you need to lift one end of your board off the snow and reach down with both hands to contact the ground. When you do it correctly, you make a three-point connection with the ground, just like a tripod!",
        "The next time you catch some air, reach back to grab the tail of your snowboard.",
        "The boardslide is like a 50-50, except you turn your board perpendicular to the rail so you can slide down it sideways.",
        "Take care when trying a backflip. You’ll need plenty of time and space to complete the flip before you land.",
        "The full-circle version of a Frontside 180.",
        "The full-circle version of a Backside 180.",
        "The front roll moves your body in a forward motion, but it tilts a little to the side. Master it before moving on to a full front flip.",
        "The front flip is harder than the backflip because you have to resist the upward motion you get from your jump. Instead, lean forward and tuck your body to rotate forward.",
        "You probably already guessed. It’s a frontside rotation that turns you more than one full circle.",
        "The opposite of a frontside 360+. Maintaining balance gets difficult when you move backward while spinning.",
    ];

    private static array $PictureNameList = [
        'php1F6E-632883706783a.jpg',
        'php4C6E-645637fg6783a.jpg',
        'php23C9-632544df34343.jpg',
        'php24E79-3454356a6e1f.jpg',
        'php29F7-632884fba6e1f.jpg',
        'php29F8-632884fba83b9.jpg',
        'php29F79-6324356a6e1f.jpg'
    ];

    private static array $videoLinkList = [
        "LyfFuv4_wjQ",
        "SFYYzy0UF-8",
        "GS9MMT_bNn8",
        "Mr4tsSzsWOs",
        "RUiWxRCAvLs",
        "8M5MJO8Z4zw"
    ];


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


        for ($i = 0; $i <= count(self::$categoryNameArray) - 1; $i++) {
            $category = new Category();
            $category->setName(self::$categoryNameArray[$i])->setSlug(self::$categorySlugArray[$i]);
            $this->addReference(self::$categoryNameArray[$i], $category);
            $manager->persist($category);
        }
        $manager->flush();


        for ($i = 0; $i < (count(self::$categoryNameArray) - 1) ; $i++) {
            $trick = new Trick();
            $trick->setName(self::$trickNameArray[$i])
                ->setSlug(self::$trickSlugArray[$i])
                ->addCategory($this->getReference(self::$categoryNameArray[rand(0, 20)]))
                ->setDescription(self::$trickDescriptionArray[$i])
                ->setPublicationStatusTrick("Published")
                ->SetUser($this->getReference(self::ADMIN_USER_REFERENCE));
            $manager->persist($trick);

            for ($j = 1; $j <= rand(1, 6); $j++) {
                $picture = new Pictures();
                $picture->setPictureLink(self::$PictureNameList[rand(0,count(self::$PictureNameList)-1)])
                    ->setPictureName("Name of picture N°" . $j . " of trick n°" . $i)
                    ->setTrick($trick)
                    ->setUser($this->getReference(self::ADMIN_USER_REFERENCE));
                $manager->persist($picture);
            }

            for ($j = 1; $j <= rand(1, 3); $j++) {
                $video = new Video();
                $video->setVideoLink(self::$videoLinkList[rand(0,(count(self::$videoLinkList)-1))])
                    ->setVideoName("Name of video N°" . $j . " of trick n°" . $i)
                    ->setTrick($trick)
                    ->SetUser($this->getReference(AppFixtures::ADMIN_USER_REFERENCE));
                $manager->persist($video);
            }


            for ($j = 1; $j <= rand(5, 15); $j++) {
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
