<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostForm;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostController extends AbstractController
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    #[Route('/post', name: 'app_ajouter_post')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request): Response
    {
        $post = new Post();
        $postForm = $this->createForm(PostForm::class, $post);
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $imageFile = $postForm->get('image')->getData();  // Récupérer le fichier téléchargé

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid() . '.' . $imageFile->guessExtension(); // Générer un nom unique pour l'image

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Le répertoire où l'image sera stockée
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->render('pages/post/ajouter.html.twig', [
                        'postForm' => $postForm->createView(),
                    ]);
                }

                $post->setImage($newFilename); // Mettre à jour l'entité avec le chemin de l'image
            }

            $existingPost = $this->postRepository->findOneBy(['titre' => $post->getTitre()]);

            if ($existingPost) {
                $this->addFlash('error', 'Le post existe déjà');
                return $this->render('pages/post/ajouter.html.twig', [
                    'postForm' => $postForm->createView(),
                ]);
            }

            $this->postRepository->sauvegarder($post);

            $this->addFlash('success', 'Post ajouté !');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('pages/post/ajouter.html.twig', [
            'postForm' => $postForm->createView(),
        ]);
    }

    #[Route('/post', name: 'app_post_redirect')]
    public function redirectToLogin(): RedirectResponse
    {
        $this->addFlash('error', 'Vous devez être connecté pour accéder à votre profil.');

        return $this->redirectToRoute('app_profil');
    }

    #[Route('/posts', name: 'app_liste_posts')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function liste(): Response
    {
        $posts = $this->postRepository->findAll();

        return $this->render('pages/post/liste.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/{id}/modifier', name: 'app_modifier_post', methods: ['GET', 'POST'])]
    public function modifier(int $id, Request $request): Response
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            $this->addFlash('error', "Le post n'existe pas.");
            return $this->redirectToRoute('app_profil');
        }

        $postForm = $this->createForm(PostForm::class, $post);
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {

            $this->postRepository->sauvegarder($post, true);

            $this->addFlash('success', 'post mis à jour avec succès !');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('pages/post/modifier.html.twig', [
            'postForm' => $postForm->createView(),
        ]);
    }

    #[Route("/post/{id}/supprimer", name: "app_supprimer_post", methods: ["GET", "POST"])]

    function supprimer($id, PostRepository $repo)
    {
        $post = $repo->find($id);

        if (!$post) {
            $this->addFlash('error', "Le post n'existe pas.");
            return $this->redirectToRoute('app_profil');
        } else {

            $repo->supprimer($post);
            $this->addFlash('success', 'Le post a été supprimé avec succès !');
            return $this->redirectToRoute('app_profil');
        }
    }
}
