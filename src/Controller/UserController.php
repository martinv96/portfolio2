<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ConnexionForm;
use App\Form\UserInscriptionForm;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    private UtilisateurRepository $userRepository;

    public function __construct(UtilisateurRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/inscription', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Utilisateur();
        $inscriptionForm = $this->createForm(UserInscriptionForm::class, $user);
        $inscriptionForm->handleRequest($request);

        if ($inscriptionForm->isSubmitted() && $inscriptionForm->isValid()) {

            $existingUser = $this->userRepository->findOneBy(['email' => $user->getEmail()]);

            if ($existingUser) {

                $this->addFlash('error', 'Un compte avec cet email existe déjà. Veuillez utiliser un mail différent.');

                return $this->render('pages/user/inscription.html.twig', [
                    'inscriptionForm' => $inscriptionForm->createView(),
                ]);
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $this->userRepository->sauvegarder($user);

            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_inscription');
        }

        return $this->render('pages/user/inscription.html.twig', [
            'inscriptionForm' => $inscriptionForm->createView(),
        ]);
    }

    #[Route('/connexion', name: 'app_connexion', methods: ['GET', 'POST'])]
public function connexion(): Response
{
    return $this->render('pages/user/connexion.html.twig', [
        'loginForm' => $this->createForm(ConnexionForm::class)->createView(),
    ]);
}

#[Route("/logout", name: "app_logout")]
    function logout() {}
}
