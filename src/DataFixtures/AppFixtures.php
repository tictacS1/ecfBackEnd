<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Emprunteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['prod', 'test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadAdmin();
        $this->loadAdminEmp();
        $this->loadAdminEmpId();
    }

    public function loadAdminEmp(): void
    {
        $repositoryUser = $this->manager->getRepository(User::class);

        $user_id_1 = $repositoryUser->find(1);

        $emprunteur = new Emprunteur();
        $emprunteur->setNom('Quoi');
        $emprunteur->setPrenom('Coubeh');
        $emprunteur->setTel('0742036069');
        $emprunteur->setUser($user_id_1);

        $this->manager->persist($emprunteur);

        $this->manager->flush();
    }

    public function loadAdmin(): void
    {
        $repositoryEmprunteur = $this->manager->getRepository(Emprunteur::class);

        $emprunteur_id_1 = $repositoryEmprunteur->find(1);

        $datas = [
            [
                'email' => 'admin@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
                'enabled' => true,
                'emprunteur_id' => $emprunteur_id_1,
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setEnabled($data['enabled']);
            $user->setEmprunteur($data['emprunteur_id']);

            $this->manager->persist($user);
        }
        $this->manager->flush();
    }

    public function loadAdminEmpId(): void
    {
        $repository = $this->manager->getRepository(Emprunteur::class);
        $repositoryUser = $this->manager->getRepository(User::class);

        $user1 = $repositoryUser->find(1);

        $emprunteur_id_1 = $repository->find(1);

        $user1->setEmprunteur($emprunteur_id_1);
        $this->manager->persist($user1);

        $this->manager->flush();
    }
}
