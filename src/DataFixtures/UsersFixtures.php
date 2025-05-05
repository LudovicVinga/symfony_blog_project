<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = $this->createUser1();
        $user2 = $this->createUser2();
        $user3 = $this->createUser3();

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);

        $manager->flush();
    }


    /**
     * Permet de créer Eren
     *
     * @return User
     */
    private function createUser1(): User
    {
        $user1 = new User();

        $passwordHashed = $this->hasher->hashPassword($user1, 'azerty1234A!');

        $user1->setFirstName('Eren');
        $user1->setLastName('Jagger');
        $user1->setEmail('eren@jagger.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setIsVerified(true);
        $user1->setPassword($passwordHashed);
        $user1->setCreatedAt(new DateTimeImmutable());
        $user1->setUpdatedAt(new DateTimeImmutable());
        $user1->setVerifiedAt(new DateTimeImmutable());

        return $user1;
    }


    /**
     * Permet de créer Mikasa
     *
     * @return User
     */
    private function createUser2(): User
    {
        $user2 = new User();

        $passwordHashed = $this->hasher->hashPassword($user2, 'azerty1234A!');

        $user2->setFirstName('Mikasa');
        $user2->setLastName('Ackerman');
        $user2->setEmail('mikasa@ackerman.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setIsVerified(true);
        $user2->setPassword($passwordHashed);
        $user2->setCreatedAt(new DateTimeImmutable());
        $user2->setUpdatedAt(new DateTimeImmutable());
        $user2->setVerifiedAt(new DateTimeImmutable());

        return $user2;
    }


    /**
     * Permet de créer Livai
     *
     * @return User
     */
    private function createUser3(): User
    {
        $user3 = new User();

        $passwordHashed = $this->hasher->hashPassword($user3, 'azerty1234A!');

        $user3->setFirstName('Livai');
        $user3->setLastName('Ackerman');
        $user3->setEmail('livai@ackerman.com');
        $user3->setRoles(['ROLE_USER']);
        $user3->setIsVerified(true);
        $user3->setPassword($passwordHashed);
        $user3->setCreatedAt(new DateTimeImmutable());
        $user3->setUpdatedAt(new DateTimeImmutable());
        $user3->setVerifiedAt(new DateTimeImmutable());

        return $user3;
    }
}
