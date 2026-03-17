<?php
namespace App\Controller;

use App\Service\PanierService;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsagerRepository;
use Doctrine\ORM\EntityManagerInterface;

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
    public function ajouter(int $idProduit, int $quantite, PanierService $panierService, ProduitRepository $produitRepository): Response
    {
        if (!$produitRepository->find($idProduit)) {
            throw $this->createNotFoundException("Le produit n'existe pas.");
        }

        $panierService->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/enlever/{idProduit}/{quantite}', name: 'app_panier_enlever', requirements: ['idProduit' => '\d+', 'quantite' => '\d+'], defaults: ['quantite' => 1])]
    public function enlever(int $idProduit, int $quantite, PanierService $panierService): Response
    {
        $panierService->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/supprimer/{idProduit}', name: 'app_panier_supprimer', requirements: ['idProduit' => '\d+'])]
    public function supprimer(int $idProduit, PanierService $panierService): Response
    {
        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/vider', name: 'app_panier_vider')]
    public function vider(PanierService $panierService): Response
    {
        $panierService->vider();
        return $this->redirectToRoute('app_panier_index');
    }


    #[Route('/commander', name: 'app_panier_commander')]
    public function commander(PanierService $panierService, EntityManagerInterface $em): Response
    {
        $usager = $this->getUser();
        if (!$usager) {
            return $this->redirectToRoute('app_login');
        }
        $commande = $panierService->panierToCommande($usager);
        if ($commande) {
            return $this->render('panier/commande.html.twig', ['commande' => $commande]);
        }
        return $this->redirectToRoute('app_panier_index');
    }

    public function nombreProduits(PanierService $panierService): Response
    {
        return new Response((string)$panierService->getNombreProduits());
    }


}
