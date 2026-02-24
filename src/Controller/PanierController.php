<?php
namespace App\Controller;

use App\Service\PanierService;
use App\Service\BoutiqueService; // Importation nécessaire pour vérifier les produits
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/panier', requirements: ['_locale' => 'fr|en'])]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index')]
    public function index(PanierService $panierService): Response
    {
        return $this->render('panier/index.html.twig', [
            'items' => $panierService->getContenu(),
            'total' => $panierService->getTotal(),
        ]);
    }

    #[Route('/ajouter/{idProduit}/{quantite}', name: 'app_panier_ajouter', requirements: ['idProduit' => '\d+', 'quantite' => '\d+'], defaults: ['quantite' => 1])]
    public function ajouter(int $idProduit, int $quantite, PanierService $panierService, BoutiqueService $boutique): Response
    {
        if (!$boutique->findProduitById($idProduit)) {
            throw $this->createNotFoundException("Impossible d'ajouter : le produit n°$idProduit n'existe pas.");
        }

        $panierService->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/enlever/{idProduit}/{quantite}', name: 'app_panier_enlever', requirements: ['idProduit' => '\d+', 'quantite' => '\d+'], defaults: ['quantite' => 1])]
    public function enlever(int $idProduit, int $quantite, PanierService $panierService, BoutiqueService $boutique): Response
    {
        if (!$boutique->findProduitById($idProduit)) {
            throw $this->createNotFoundException("Impossible d'enlever : le produit n°$idProduit n'existe pas.");
        }

        $panierService->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/supprimer/{idProduit}', name: 'app_panier_supprimer', requirements: ['idProduit' => '\d+'])]
    public function supprimer(int $idProduit, PanierService $panierService, BoutiqueService $boutique): Response
    {
        if (!$boutique->findProduitById($idProduit)) {
            throw $this->createNotFoundException("Impossible de supprimer : le produit n°$idProduit n'existe pas.");
        }

        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/vider', name: 'app_panier_vider')]
    public function vider(PanierService $panierService): Response
    {
        $panierService->vider();
        return $this->redirectToRoute('app_panier_index');
    }

    public function nombreProduits(PanierService $panierService): Response
    {
        $nb = $panierService->getNombreProduits();
        return new Response((string)$nb);
    }
}
