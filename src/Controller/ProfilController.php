<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        return $this->render('pages/profil/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/profil', name: 'app_profil_redirect')]
    public function redirectToLogin(): RedirectResponse
    {
        $this->addFlash('error', 'Vous devez être connecté pour accéder à votre profil.');

        return $this->redirectToRoute('app_profil');
    }
}
