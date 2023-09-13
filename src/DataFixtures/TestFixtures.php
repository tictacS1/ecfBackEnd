<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Emprunteur;
use App\Entity\Emprunt;
use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
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
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        //$this->loadUsersStatic();
        $this->loadUsersDyn();

        //$this->loadEmprunteursStatic();
        $this->loadEmprunteursDyn();

        //$this->loadAuteursStatic();
        $this->loadAuteursDyn();

        $this->loadGenres();

        //$this->loadLivresStatic();
        $this->loadLivresDyn();

        //$this->loadEmpruntsStatic();
        $this->loadEmpruntsDyn();
    }

    public function loadUsersStatic(): void
    {
        $datas = [
            [
                'email' => 'test1@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
                'enabled' => true,
            ],
            [
                'email' => 'test2@gmail.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true,
            ],
            [
                'email' => 'test3@gmail.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => false,
            ],
        ];

        foreach($datas as $data) {
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

    public function loadUsersDyn(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->unique()->email());
            $user->setRoles(['ROLE_USER']);
            $password = $this->faker->password();
            $hash = $this->hasher->hashPassword($user, $password);
            $user->setPassword($hash);
            $user->setEnabled($this->faker->boolean());

            $this->manager->persist($user);
        }
        $this->manager->flush();
    }

    public function loadEmprunteursDyn(): void
    {
        $repository = $this->manager->getRepository(User::class);
        $user = $repository->findAll();
        
        for ($i = 0; $i < 100; $i++) {
            $emprunteur = new Emprunteur();
            $emprunteur->setNom($this->faker->lastName());
            $emprunteur->setPrenom($this->faker->firstname());
            $emprunteur->setTel($this->faker->phoneNumber());
            $id = $this->faker->unique()->randomElement($user);
            $emprunteur->setUser($id);
            $this->manager->persist($emprunteur);
        }
        $this->manager->flush();
    }

    public function loadEmprunteursStatic(): void
    {
        $repository = $this->manager->getRepository(User::class);
        
        $user_id_1 = $repository->find(2);
        $user_id_2 = $repository->find(3);
        $user_id_3 = $repository->find(4);

        $datas = [
            [
                'nom' => 'Edward',
                'prenom' => 'Thomas',
                'tel' => '0709864576',
                'user_id' => $user_id_1,
            ],
            [
                'nom' => 'Colonel',
                'prenom' => 'Sanders',
                'tel' => '0673223093',
                'user_id' => $user_id_2,
            ],
            [
                'nom' => 'Lucas',
                'prenom' => 'Padidé',
                'tel' => '0763489244',
                'user_id' => $user_id_3,
            ],
        ];
        
        foreach($datas as $data) {
            $emprunteur = new Emprunteur();
            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTel($data['tel']);
            $emprunteur->setUser($data['user_id']);

            $this->manager->persist($emprunteur);

        }
        $this->manager->flush();
    }

    public function loadAuteursStatic(): void
    {
        $datas = [
            [
                'nom' => 'Auteur Inconnu',
                'prenom' => '',
            ],
            [
                'nom' => 'Lawrence',
                'prenom' => 'Edward',
            ],
            [
                'nom' => 'Danielewski', 
                'prenom' => 'Mark',
            ],
            [
                'nom' => 'Tolkien',
                'prenom' => 'John',
            ],
        ];
        
        foreach($datas as $data) {
            $auteur = new Auteur();
            $auteur->setNom($data['nom']);
            $auteur->setPrenom($data['prenom']);

            $this->manager->persist($auteur);
        }
        $this->manager->flush();

        for ($i = 0; $i < 500; $i++) {
            $auteur = new Auteur();
            $auteur->setNom($this->faker->lastName());
            $auteur->setPrenom($this->faker->firstname());
            $this->manager->persist($auteur);
        }
        $this->manager->flush();
    }

    public function loadAuteursDyn(): void
    {
        for ($i = 0; $i < 500; $i++) {
            $auteur = new Auteur();
            $auteur->setNom($this->faker->lastName());
            $auteur->setPrenom($this->faker->firstname());
            $this->manager->persist($auteur);
        }
        $this->manager->flush();
    }

    public function loadGenres(): void
    {
        $datas = [
            [
                'nom' => 'Poésie',
                'description' => null,
            ],
            [
                'nom' => 'Nouvelle',
                'description' => null,
            ],
            [
                'nom' => 'Roman Historique',
                'description' => null,
            ],
            [
                'nom' => "Roman d'Amour",
                'description' => null,
            ],
            [
                'nom' => "Roman d'Aventure",
                'description' => null,
            ],
            [
                'nom' => 'Science-fiction', 
                'description' => null,
            ],
            [
                'nom' => 'Fantasy',
                'description' => null,
            ],
            [
                'nom' => 'Biographie',
                'description' => null,
            ],
            [
                'nom' => 'Conte',
                'description' => null,
            ],
            [
                'nom' => 'Témoignage', 
                'description' => null,
            ],
            [
                'nom' => 'Théâtre',
                'description' => null,
            ],
            [
                'nom' => 'Essai',
                'description' => null,
            ],
            [
                'nom' => 'Journal Intime',
                'description' => null,
            ],
        ];
        
        foreach($datas as $data) {
            $genre = new Genre();
            $genre->setNom($data['nom']);
            $genre->setDescription($data['description']);

            $this->manager->persist($genre);
        }
        $this->manager->flush();
    }

    public function loadLivresStatic(): void
    {
        $repository = $this->manager->getRepository(Auteur::class);
        $repositoryGenre = $this->manager->getRepository(Genre::class);
        
        $auteur_id_1 = $repository->find(1);
        $auteur_id_2 = $repository->find(2);
        $auteur_id_3 = $repository->find(3);
        $auteur_id_4 = $repository->find(4);

        $genre_id_1 = $repositoryGenre->find(1);
        $genre_id_2 = $repositoryGenre->find(2);
        $genre_id_3 = $repositoryGenre->find(3);
        $genre_id_4 = $repositoryGenre->find(4);

        $datas = [
            [
                'titre' => 'Lorem ipsum dolor sit amet',
                'annee_edition' => 2010,
                'nombre_pages' => 100,
                'code_isbn' => 9785786930024,
                'auteur_id' => $auteur_id_1,
                'genre_id' => $genre_id_1,
            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'annee_edition' => 2011,
                'nombre_pages' => 150,
                'code_isbn' => 9783817260935,
                'auteur_id' => $auteur_id_2,
                'genre_id' => $genre_id_2,
            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'annee_edition' => 2012,
                'nombre_pages' => 200,
                'code_isbn' => 9782020493727,
                'auteur_id' => $auteur_id_3,
                'genre_id' => $genre_id_3,
            ],
            [
                'titre' => 'Quem audis satis belle',
                'annee_edition' => 2013,
                'nombre_pages' => 250,
                'code_isbn' => 9794059561353,
                'auteur_id' => $auteur_id_4,
                'genre_id' => $genre_id_4,
            ],
        ];
        
        foreach($datas as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setAneeEdition($data['annee_edition']);
            $livre->setNombrePages($data['nombre_pages']);
            $livre->setCodeIsbn($data['code_isbn']);
            $livre->setAuteur($data['auteur_id']);
            $livre->addGenre($data['genre_id']);

            $this->manager->persist($livre);
        }
        $this->manager->flush();
    }

    public function loadLivresDyn(): void
    {
        $repository = $this->manager->getRepository(Auteur::class);
        $repositoryGenre = $this->manager->getRepository(Genre::class);
        
        $auteurs = $repository->findAll();
        $genres = $repositoryGenre->findAll();

        for ($i = 0; $i < 1000; $i++) {
            $livre = new Livre();
            $livre->setTitre($this->faker->word());
            $livre->setAneeEdition($this->faker->year());
            $livre->setNombrePages($this->faker->numberBetween(70,400));
            $livre->setCodeIsbn($this->faker->numberBetween(9000000000000,9999999999999));
            $auteur_id = $this->faker->randomElement($auteurs);
            $livre->setAuteur($auteur_id);
            $genre_id = $this->faker->randomElement($genres);
            $livre->addGenre($genre_id);

            $this->manager->persist($livre);
        }
        $this->manager->flush();
    }

    public function loadEmpruntsStatic(): void
    {
        $repositoryEmprunteur = $this->manager->getRepository(Emprunteur::class);
        $repositoryLivre = $this->manager->getRepository(Livre::class);
        
        $emprunteur_id_1 = $repositoryEmprunteur->find(1);
        $emprunteur_id_2 = $repositoryEmprunteur->find(2);
        $emprunteur_id_3 = $repositoryEmprunteur->find(3);

        $livre_id_1 = $repositoryLivre->find(1);
        $livre_id_2 = $repositoryLivre->find(2);
        $livre_id_3 = $repositoryLivre->find(3);

        $datas = [
            [
                'date_emprunt' => new DateTime('2020-02-01 10:00:00'),
                'date_retour' =>  new DateTime('2020-03-01 10:00:00'),
                'emprunteur_id' => $emprunteur_id_1,
                'livre_id' => $livre_id_1,
            ],
            [
                'date_emprunt' => new DateTime('2020-03-01 10:00:00'),
                'date_retour' =>  new DateTime('2020-04-01 10:00:00'),
                'emprunteur_id' => $emprunteur_id_2,
                'livre_id' => $livre_id_2,
            ],
            [
                'date_emprunt' => new DateTime('2020-04-01 10:00:00'),
                'date_retour' =>  null,
                'emprunteur_id' => $emprunteur_id_3,
                'livre_id' => $livre_id_3,
            ],
        ];
        
        foreach($datas as $data) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($data['date_emprunt']);
            $emprunt->setDateRetour($data['date_retour']);
            $emprunt->setEmprunteur($data['emprunteur_id']);
            $emprunt->setLivre($data['livre_id']);

            $this->manager->persist($emprunt);
        }
        $this->manager->flush();
    }

    public function loadEmpruntsDyn(): void
    {
        $repositoryEmprunteur = $this->manager->getRepository(Emprunteur::class);
        $repositoryLivre = $this->manager->getRepository(Livre::class);
        
        $emprunteurs = $repositoryEmprunteur->findAll();
        $livres = $repositoryLivre->findAll();

        for ($i = 0; $i < 200; $i++) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($this->faker->dateTimeBetween('-5 week' , '-3 week'));
            $emprunt->setDateRetour($this->faker->optional($weight = 0.6 , $default = null )->dateTimeBetween('-2 week','now'));
            $emprunteur_id = $this->faker->randomElement($emprunteurs);
            $emprunt->setEmprunteur($emprunteur_id);
            $livre_id = $this->faker->randomElement($livres);
            $emprunt->setLivre($livre_id);

            $this->manager->persist($emprunt);
        }
        $this->manager->flush();
    }
}