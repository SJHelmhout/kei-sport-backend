<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'users';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('testuser1@gym.nl');
        $user1->setPlainPassword('test');
        $user1->setRoles(['ROLE_USER']);
        $user1->setName('Test User1');

        $user2 = new User();
        $user2->setEmail('testuser2@gym.nl');
        $user2->setPlainPassword('test');
        $user2->setRoles(['ROLE_USER']);
        $user2->setName('Test User2');

        $manager->persist($user1);
        $manager->persist($user2);

        $this->setReference(self::USER_REFERENCE, $user1);
        $manager->flush();
    }
}