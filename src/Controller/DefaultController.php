<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    // Cette route attrape le "vide" (localhost:8000) et redirige vers le français
    #[Route('/')]
    public function redirectNoLocale(): Response
    {
        return $this->redirectToRoute('app_default_index', ['_locale' => 'fr']);
    }

    // Cette route gère les vraies pages /fr/ et /en/
    #[Route('/{_locale}/', name: 'app_default_index', requirements: ['_locale' => 'fr|en'])]
    public function index(): Response
    {
        $now = new \DateTime("now");
        return $this->render('default/index.html.twig', [
            "dateActuelle" => $now,
        ]);
    }

    // On ajoute { _locale } devant contact pour que le bouton de langue fonctionne aussi ici
    #[Route('/{_locale}/contact', name: 'app_default_contact', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
    public function contact(): Response
    {
        return $this->render('default/contact.html.twig');
    }

    #[Route('/{_locale}/test', name: 'app_default_test', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
    public function test(): Response
    {
        return new Response("Hello World !");
    }
}
