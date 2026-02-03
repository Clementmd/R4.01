<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/boutique/rayon/{idCategorie}', name: 'app_boutique_rayon')] // Nouveau nom ici !
    public function rayon(int $idCategorie,BoutiqueService $boutiqueService): Response
    {
        // Attention : findProduitsByCategorie doit être défini quelque part (Service ou Repository)
        $Produits = $boutiqueService->findProduitsByCategorie($idCategorie);

        return $this->render('boutique/rayon.html.twig', [
            "Produits" => $Produits,
        ]);
    }
}
