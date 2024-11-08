<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Form\CollectionForm;
use App\Repository\CollectionRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CollectionController extends AbstractController
{
    private CollectionRepository $collectionRepository;

    public function __construct(CollectionRepository $collectionRepository)
    {
        $this->collectionRepository = $collectionRepository;
    }

    #[Route('/collection', name: 'app_ajouter_collection')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request): Response
    {

        $collection = new Collection();
        $collectionForm = $this->createForm(CollectionForm::class, $collection);
        $collectionForm->handleRequest($request);

        if ($collectionForm->isSubmitted() && $collectionForm->isValid()) {

            $existingcollection = $this->collectionRepository->findOneBy(['titre' => $collection->getTitre()]);

            if ($existingcollection) {

                $this->addFlash('error', 'la collection existe déjà');

                return $this->render('pages/collection/ajouter.html.twig', [
                    'collectionForm' => $collectionForm->createView(),
                ]);
            }

            $this->collectionRepository->sauvegarder($collection);

            $this->addFlash('success', 'Collection ajouté !');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('pages/collection/ajouter.html.twig', [
            'collection' => $this->getUser(),
            'collectionForm' => $this->createForm(CollectionForm::class)->createView(),
        ]);
    }

    #[Route('/collection', name: 'app_collection_redirect')]
    public function redirectToLogin(): RedirectResponse
    {
        $this->addFlash('error', 'Vous devez être connecté pour accéder à votre profil.');

        return $this->redirectToRoute('app_profil');
    }

    #[Route('/collections', name: 'app_liste_collections')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function liste(): Response
    {
        $collections = $this->collectionRepository->findAll();

        return $this->render('pages/collection/liste.html.twig', [
            'collections' => $collections,
        ]);
    }

    #[Route('/collection/{id}/modifier', name: 'app_modifier_collection', methods: ['GET', 'POST'])]
    public function modifier(int $id, Request $request): Response
    {
        $collection = $this->collectionRepository->find($id);

        if (!$collection) {
            $this->addFlash('error', "La collection n'existe pas.");
            return $this->redirectToRoute('app_profil');
        }

        $collectionForm = $this->createForm(CollectionForm::class, $collection);
        $collectionForm->handleRequest($request);

        if ($collectionForm->isSubmitted() && $collectionForm->isValid()) {

            $this->collectionRepository->sauvegarder($collection, true);

            $this->addFlash('success', 'Collection mise à jour avec succès !');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('pages/collection/modifier.html.twig', [
            'collectionForm' => $collectionForm->createView(),
        ]);
    }

    #[Route("/collection/{id}/supprimer", name: "app_supprimer_collection", methods: ["GET", "POST"])]

    function supprimer($id, CollectionRepository $repo)
    {
        $collection = $repo->find($id);

        if (!$collection) {
            $this->addFlash('error', "La collection n'existe pas.");
            return $this->redirectToRoute('app_profil');
        } else {

            $repo->supprimer($collection);
            $this->addFlash('success', 'La collection a été supprimée avec succès !');
            return $this->redirectToRoute('app_profil');
        }
    }


    #[Route("/collection{id}", name:"app_detail_collection", methods:["GET","POST"])]

    public function detail(int $id, CollectionRepository $collectionRepository, PostRepository $postRepository): Response
    {
        
        $collection = $collectionRepository->find($id);

        if (!$collection) {
            throw $this->createNotFoundException('Collection non trouvée');
        }

        $posts = $postRepository->findBy(['collection' => $collection]);

        return $this->render('pages/collection/detail.html.twig', [
            'collection' => $collection,
            'posts' => $posts,
        ]);
    }
}
