<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class TopVentesController extends AbstractController
{
    public function topVentes(ProduitRepository $repo) {
        $tops = $repo->findTopVendus(5);
        return $this->render('topVentes/top_ventes.html.twig', ['listeTop' => $tops]);
    }
}
