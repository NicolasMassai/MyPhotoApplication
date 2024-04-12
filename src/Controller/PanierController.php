<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{

    #[Route('/panier', name: 'app_panier')]
    public function showCart(SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        return $this->render('panier/index.html.twig', [
            'cart' => $cart,
        ]);
    }
   
}
