<?php


namespace App\DataFixtures;

use App\Entity\Accessory;
use App\Entity\Comment;
use App\Entity\Loan;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadTags($manager);
        $this->loadAccessories($manager);
        $this->loadLoans($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function loadTags(ObjectManager $manager): void
    {
        foreach ($this->getTagData() as $name) {
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
            $this->addReference('tag-' . $name, $tag);
        }

        $manager->flush();
    }

    private function loadAccessories(ObjectManager $manager): void
    {
        foreach ($this->getAccessoryData() as [$name, $manufacturer, $model, $url, $image, $content, $quantity]) {
            $accessory = new Accessory();
            $accessory->setName($name);
            $accessory->setManufacturer($manufacturer);
            $accessory->setModel($model);
            $accessory->setUrl($url);
            $accessory->setImage($image);
            $accessory->setContent($content);
            $accessory->setQuantity($quantity);

            $manager->persist($accessory);
            $this->addReference($model, $accessory);
        }

        $manager->flush();
    }

    private function loadLoans(ObjectManager $manager): void
    {
        foreach ($this->getLoanData() as [$username, $accessoryModel]) {
            $loan = new Loan();
            $loan->setUser($this->getReference($username));
            $loan->setAccessory($this->getReference($accessoryModel));

            $manager->persist($loan);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }

    private function getTagData(): array
    {
        return [
            'lorem',
            'ipsum',
            'consectetur',
            'adipiscing',
            'incididunt',
            'labore',
            'voluptate',
            'dolore',
            'pariatur',
        ];
    }

    private function getAccessoryData(): array
    {
        return [
            [
                'Laxco SeBa Pro4 Series Digital Microscope System',
                'Laxco',
                'SEBAP4BF1',
                'https://www.fishersci.com/shop/products/seba-pro4-series-digital-microscope-system-4/SEBAP4BF1',
                '/images/micro.png',
                'Laxco SeBa Pro4 Series Digital Microscope System integrates digital technology with high quality microscope optics into seamless easy-to-use system.',
                10
            ],

            [
                'Laxco MZS32 Series Stereo Microscope',
                'Laxco',
                'MZS32321',
                'https://www.fishersci.com/shop/products/mzs32-series-stereo-microscope-4/MZS32321',
                '/images/micro2.png',
                'Laxco™ MZS32 Series Stereo Microscope combines advanced contrast methods to deliver brilliant resolution for wide range of sample types. Its expandability to fluorescence makes this the ideal system for a growing labs needs.',
                15
            ]
        ];
    }

    private function getLoanData(): array
    {
        return [
            ['jane_admin', 'SEBAP4BF1'],
            ['jane_admin', 'MZS32321'],
            ['john_user', 'MZS32321']
        ];
    }
}
