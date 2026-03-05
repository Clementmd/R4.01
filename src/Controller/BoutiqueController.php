<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}', requirements: ['_locale' => '%app.supported_locales%'], defaults: ['_locale' => 'fr'])]
final class BoutiqueController extends AbstractController
{
    #[Route('/boutique', name: 'app_boutique_index')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('boutique/index.html.twig', [
            "categories" => $categorieRepository->findAll(),
        ]);
    }

    #[Route(path: '/chercher/{recherche}', name: 'app_boutique_chercher',
        requirements: ['recherche' => '.+'],
        defaults: ['recherche' => ''])]
    public function chercher(ProduitRepository $produitRepository, string $recherche): Response
    {
        $produits = $produitRepository->findByLibelleOrTexte($recherche);

        return $this->render('boutique/chercher.html.twig', [
            "produits" => $produits,
            "recherche" => $recherche
        ]);
    }

    #[Route('/boutique/rayon/{idCategorie}', name: 'app_boutique_rayon', requirements: ['idCategorie' => '\d+'])]
    public function rayon(int $idCategorie, CategorieRepository $categorieRepository): Response
    {
        $categorie = $categorieRepository->find($idCategorie);

        if (!$categorie) {
            throw $this->createNotFoundException("Ce rayon n'existe pas.");
        }

        return $this->render('boutique/rayon.html.twig', [
            "Produits"  => $categorie->getProduits(),
            "categorie" => $categorie,
        ]);
    }
}
