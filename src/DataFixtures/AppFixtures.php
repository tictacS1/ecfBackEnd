<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
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
    }

    public function loadAdmin(): void
    {
        $datas = [
            [
                'email' => 'admin@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
                'enabled' => true,
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setEnabled($data['enabled']);

            $this->manager->persist($user);
        }
        $this->manager->flush();
    }
}
