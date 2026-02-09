<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}', requirements: ['_locale' => '%app.supported_locales%'])]
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
