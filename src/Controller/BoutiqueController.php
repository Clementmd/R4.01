<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}', requirements: ['_locale' => '%app.supported_locales%'],defaults: ['_locale' => 'fr'])]
final class BoutiqueController extends AbstractController
{
    // BoutiqueController.php

    #[Route('/boutique', name: 'app_boutique_index')] // Nom unique pour l'index
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('boutique/index.html.twig',[
            "categories" => $categories,
        ]);
    }
    #[Route(path: '/chercher/{recherche}', name: 'app_boutique_chercher',
        requirements: ['recherche' => '.+'],
        defaults: ['recherche' => ''])]

    public function chercher(ProduitRepository $produitRepository,string $recherche) : Response{
        $produits = $produitRepository->findByLibelleOrTexte($recherche);

        return $this->render('boutique/chercher.html.twig',[
            "produits" => $produits,
            "recherche" => $recherche
        ]);
    }


    #[Route('/boutique/rayon/{idCategorie}', name: 'app_boutique_rayon', requirements: ['idCategorie' => '\d+'])]
    public function rayon(int $idCategorie, CategorieRepository $categorieRepository,ProduitRepository $produitRepository): Response
    {
        // On récupère les catégories
        $categorie = $categorieRepository->find($idCategorie);

        return $this->render('boutique/rayon.html.twig', [
            "Produits"  => $produitRepository->find(['categorie' => $idCategorie]),
            "categorie" => $categorie,
        ]);
    }
}
