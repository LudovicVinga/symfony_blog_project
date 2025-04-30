<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class SuperAdminFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $superAdmin = $this->createSuperAdmin();

        $manager->persist($superAdmin);

        $manager->flush();
    }

    /**
     * Cette méthode permet de créer le super administrateur.
     * 
     * @return User
     */
    private function createSuperAdmin(): User
    {
        $superAdmin = new User();

        $passwordHashed = $this->hasher->hashPassword($superAdmin, 'azerty1234A!');

        $superAdmin->setFirstName('Jean');
        $superAdmin->setLastName('Dupont');
        $superAdmin->setEmail('medecine-du-monde@gmail.com');
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER']);
        $superAdmin->setPassword($passwordHashed);
        $superAdmin->setIsVerified(true);
        $superAdmin->setCreatedAt(new DateTimeImmutable());
        $superAdmin->setUpdatedAt(new DateTimeImmutable());
        $superAdmin->setVerifiedAt(new DateTimeImmutable());

        return $superAdmin;
    }
}
