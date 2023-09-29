<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\User;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/user', name: 'app_test_user')]
    public function user(ManagerRegistry $doctrine): Response
    {
        //appelle l'entityManager puis recup le repo User
        $em = $doctrine->getManager();
        $repository = $em->getRepository(User::class);

        // Requetes de lecture :
        $allUsers = $repository->findAllByEmail();

        $user1 = $repository->find(1);

        $userEmail = $repository->findOneBy([
            'email' => 'admin@gmail.com',
        ]);

        $userRole = $repository->findAllByRole();

        $userDisabled = $repository->findAllByDisabled();

        $title = 'Test des users';

        return $this->render('test/user.html.twig', [
            'title' => $title,
            'allUsers' => $allUsers,
            'user1' => $user1,
            'userEmail' => $userEmail,
            'userRole' => $userRole,  
            'userDisabled' => $userDisabled,      
        ]);
    }

    #[Route('/livre', name: 'app_test_livre')]
    public function livre(ManagerRegistry $doctrine): Response
    {
        //appelle l'entityManager puis recup le repo User
        $em = $doctrine->getManager();
        $repositoryLivre = $em->getRepository(Livre::class);
        $repositoryAuteur = $em->getRepository(Auteur::class);
        $repositoryGenre = $em->getRepository(Genre::class);
        
        // Requetes de lecture :
        $livreAll = $repositoryLivre->findAllByTitre();

        $livre1 = $repositoryLivre->find(1);

        $livreM = $repositoryLivre->findAllByField('a');

        $livreId = $repositoryLivre->findAllByAuthor();

        $livreR = $repositoryLivre->findAllByGenre('roman');

        $title = 'Test des livres';

        // Requete de création :
        $auteur = $repositoryAuteur->find(2);
        $genre = $repositoryGenre->find(6);
        $newLivre = new Livre();
        $newLivre->setTitre('Totum autem id externum');
        $newLivre->setAneeEdition('2020');
        $newLivre->setNombrePages('300');
        $newLivre->setCodeIsbn('9790412882714');
        $newLivre->setAuteur($auteur);
        $newLivre->addGenre($genre);
        $em->persist($newLivre);
        $em->flush();

        $newLivreVerif = $repositoryLivre->find(1001);
        
        // Requete de modification :
        $genre2 = $repositoryGenre->find(5);
        $livre2 = $repositoryLivre->find(2);
        $livre2->setTitre('Aperiendum est igitur');
        $livre2->addGenre($genre2);
        $em->flush();

        // Requete de supression :
        $livreSuppr = $repositoryLivre->find(176);

        if ($livreSuppr) {
            $em->remove($livreSuppr);
            $em->flush();
        }

        return $this->render('test/livre.html.twig', [
            'title' => $title,
            'livreAll' => $livreAll,
            'livre1' => $livre1,
            'livreM' => $livreM,
            'livreId' => $livreId,
            'livreR' => $livreR,
            'newLivreVerif' => $newLivreVerif,
        ]);
    }

    #[Route('/emprunteur', name: 'app_test_emprunteur')]
    public function emprunteur(ManagerRegistry $doctrine): Response
    {
        //appelle l'entityManager puis recup le repo
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Emprunteur::class);
        
        // Requetes de lecture :
        $emprunteurs = $repository->findAllByName();

        $emprunteur1 = $repository->find(3);

        $emprunteur_id = $repository->findById();

        $emprunteurs_field = $repository->findAllByField('an');

        $emprunteur_num = $repository->findAllByNum('01');

        $date = new DateTime('2023-09-07 00:00:00');
        $emprunteur_date = $repository->findAllByDate($date);

        $title = 'Test des emprunteurs';

        return $this->render('test/emprunteur.html.twig', [
            'title' => $title,
            'emprunteurs' => $emprunteurs,
            'emprunteur1' => $emprunteur1,
            'emprunteur_id' => $emprunteur_id,
            'emprunteurs_field' => $emprunteurs_field,
            'emprunteur_num' => $emprunteur_num,
            'emprunteur_date' => $emprunteur_date,
        ]);
    }

    #[Route('/emprunt', name: 'app_test_emprunt')]
    public function emprunt(ManagerRegistry $doctrine): Response
    {
        // appelle l'entityManager puis recup le repo
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Emprunt::class);
        $repositoryEmprunteur = $em->getRepository(Emprunteur::class);
        $repositoryLivre = $em->getRepository(Livre::class);

        // Requetes de lecture :
        $emprunt_new = $repository->findAllByLast();

        $emprunt2 = $repository->findAllByEmpr(5);

        $livre3 = $repository->findByLivre(14);

        $emprunt_rendu = $repository->findAllByLastRetour();

        $emprunt_null = $repository->findAllByNull();

        // Requete de création :
        $emprunteur = $repositoryEmprunteur->find(1);
        $livre = $repositoryLivre->find(1);
        $newLivre = new Emprunt();
        $newLivre->setDateEmprunt(new datetime('2020-12-01 16:00:00'));
        $newLivre->setDateRetour(null);
        $newLivre->setEmprunteur($emprunteur);
        $newLivre->setLivre($livre);
        $em->persist($newLivre);
        $em->flush();

        // Requete de modification :
        $modif = $repository->find(3);
        $modif->setDateRetour(new datetime('2023-09-10 16:00:00'));
        $em->flush();

        // Requete de suppression :
        $empruntSuppr = $repository->find(42);

        if ($empruntSuppr) {
            $em->remove($empruntSuppr);
            $em->flush();
        }

        $title = 'Test des emprunts';

        return $this->render('test/emprunt.html.twig', [
            'title' => $title,
            'emprunt_n' => $emprunt_new,
            'emprunt2' => $emprunt2,
            'livre' => $livre3,
            'emp_rendu' => $emprunt_rendu,
            'emp_null' => $emprunt_null,
        ]);
    }
}
