<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\Emprunt;
use App\Entity\User;
use App\Form\UserProfileType;
use App\Form\UserPasswordType;
use App\Repository\EmprunteurRepository;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function PHPUnit\Framework\containsOnly;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_index', methods: ['GET', 'POST'])]
    public function index(EmpruntRepository $empruntRepository,EmprunteurRepository $emprunteurRepository): Response
    {
        $active = $this->getUser();
        $emprunteur = $emprunteurRepository->find($active);
        $emprunts = $emprunteur->getEmprunts();

        // if (!$this->isGranted('ROLE_ADMIN')) {
        //     // le session user n'est pas un admin

        //     /** @var \App\Entity\User $sessionUser */
        //     $sessionUser = $this->getUser();
        //     $student = $sessionUser->getStudent();
        //     $schoolYear = $student->getSchoolYear();
        //     $users = $userRepository->findBySchoolYear($schoolYear);
        // }

        return $this->render('profile/index.html.twig', [
            'emprunts' => $empruntRepository->findAll(),
            'activeEmprunts' => $emprunts,
        ]);
    }

    #[Route('/{id}', name: 'app_profile_emprunt', methods: ['GET'])]
    public function show(Emprunt $emprunt,User $user): Response
    {
        $this->filterSessionUser($user);

        return $this->render('profile/show.html.twig', [
            'emprunt' => $emprunt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profile_user', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {   
        $this->filterSessionUser($user);
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/password', name: 'app_profile_password', methods: ['GET', 'POST'])]
    public function password(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->filterSessionUser($user);

        $form = $this->createForm(UserPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    private function filterSessionUser(User $user)
    {
        $sessionUser = $this->getUser();

        if ($sessionUser != $user) {
            // le user connecté essaie de consulter le profil d'un autre user
            throw new NotFoundHttpException("La page que vous recherchez n'existe pas.");
        }
    }

    // private function filterSessionEmprunt(Emprunt $emprunt)
    // {   
    //     $sessionUser = $this->getUser();

    //     if ($sessionUser != $user) {
    //         // le user connecté essaie de consulter le profil d'un autre user
    //         throw new AccessDeniedException();
    //     }
    // }
}
