<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}', requirements: ['_locale' => '%app.supported_locales%'],defaults: ['_locale' => 'fr'])]
final class BoutiqueController extends AbstractController
{
    // BoutiqueController.php

    #[Route('/boutique', name: 'app_boutique_index')] // Nom unique pour l'index
    public function index(BoutiqueService $boutiqueService): Response
    {
        $categories = $boutiqueService->findAllCategories();
        return $this->render('boutique/index.html.twig',[
            "categories" => $categories,
        ]);
    }
    #[Route(path: '/chercher/{recherche}', name: 'app_boutique_chercher',
        requirements: ['recherche' => '.+'],
        defaults: ['recherche' => ''])]

    public function chercher(BoutiqueService $boutique,string $recherche) : Response{
        $produits = $boutique->findProduitsByLibelleOrTexte($recherche);

        return $this->render('boutique/chercher.html.twig',[
            "produits" => $produits,
            "recherche" => $recherche
        ]);
    }


    #[Route('/boutique/rayon/{idCategorie}', name: 'app_boutique_rayon', requirements: ['idCategorie' => '\d+'])]
    public function rayon(int $idCategorie, BoutiqueService $boutiqueService): Response
    {
        // On récupère les produits
        $produits = $boutiqueService->findProduitsByCategorie($idCategorie);
        // On récupère les catégories
        $categorie = $boutiqueService->findCategorieById($idCategorie);

        return $this->render('boutique/rayon.html.twig', [
            "Produits"  => $produits,
            "categorie" => $categorie,
        ]);
    }
}
