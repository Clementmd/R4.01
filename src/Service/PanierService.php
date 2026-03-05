<?php
namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class PanierService
{
    private $session;
    private $produitRepository;
    private $panier;

    // Injection de ProduitRepository à la place de BoutiqueService
    public function __construct(RequestStack $requestStack, ProduitRepository $produitRepository)
    {
        $this->produitRepository = $produitRepository;
        $this->session = $requestStack->getSession();
        $this->panier = $this->session->get('panier', []);
    }

    public function getTotal() : float
    {
        $total = 0;
        foreach ($this->panier as $idProduit => $quantite) {
            $produit = $this->produitRepository->find($idProduit);
            if ($produit) {
                $total += $quantite * $produit->getPrix();
            }
        }
        return $total;
    }

    // Renvoie le nombre de produits dans le panier
    public function getNombreProduits() : int
    {
        return array_sum($this->panier);
    }

    // Ajouter au panier le produit $idProduit en quantite $quantite
    public function ajouterProduit(int $idProduit, int $quantite = 1) : void
    {
      if (isset($this->panier[$idProduit])) {
          $this->panier[$idProduit] += $quantite;
      }else{
          $this->panier[$idProduit] = $quantite;
      }
      $this->session->set('panier', $this->panier);
    }

    // Enlever du panier le produit $idProduit en quantite $quantite
    public function enleverProduit(int $idProduit, int $quantite = 1) : void
    {
        if (isset($this->panier[$idProduit])) {
            $this->panier[$idProduit] -= $quantite;
            if ($this->panier[$idProduit]<=0) {
                unset($this->panier[$idProduit]);
            }
        }
        $this->session->set('panier', $this->panier);
    }

    // Supprimer le produit $idProduit du panier
    public function supprimerProduit(int $idProduit) : void
    {
        if (isset($this->panier[$idProduit])) {
            unset($this->panier[$idProduit]);
            $this->session->set('panier', $this->panier);
        }
    }

    // Vider complètement le panier
    public function vider() : void
    {
        $this->panier=[];
        $this->session->remove('panier');
    }

    // Renvoie le contenu du panier dans le but de l'afficher
    //   => un tableau d'éléments [ "produit" => un objet produit, "quantite" => sa quantite ]
    public function getContenu() : array
    {
        $contenu = [];
        foreach ($this->panier as $idProduit => $quantite) {
            $produit = $this->produitRepository->find($idProduit);
            if ($produit) {
                $contenu[] = ['produit' => $produit, 'quantite' => $quantite];
            }
        }
        return $contenu;
    }
}
