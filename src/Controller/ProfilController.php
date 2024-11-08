<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\CollectionForm;
use App\Form\ProfileForm;
use App\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfilController extends AbstractController
{
    private $entityManager;
    private $collectionRepository; 

    public function __construct(EntityManagerInterface $entityManager, CollectionRepository $collectionRepository)
    {
        $this->entityManager = $entityManager;
        $this->collectionRepository = $collectionRepository;
    }

    #[Route('/profil', name: 'app_profil')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {

        $collections = $this->collectionRepository->findAll();

        return $this->render('pages/profil/index.html.twig', [
            'user' => $this->getUser(),
            'profileForm' => $this->createForm(ProfileForm::class)->createView(),
            'collection' => $collections,
        ]);
    }

    #[Route('/profil', name: 'app_profil_redirect')]
    public function redirectToLogin(): RedirectResponse
    {
        $this->addFlash('error', 'Vous devez être connecté pour accéder à votre profil.');

        return $this->redirectToRoute('app_profil');
    }

    #[Route('/profile/update', name: 'app_modifier_informations')]
    public function updateProfile(Request $request, Security $security): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('avatar')->getData();

            if ($avatarFile) {
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();
                $avatarFile->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
                $user->setAvatar($newFilename);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Les informations ont été mises à jour avec succès.');

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('pages/profil/update.html.twig', [
            'formupdate' => $form->createView(),
        ]);
    }
}
