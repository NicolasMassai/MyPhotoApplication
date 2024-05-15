<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use App\Service\Cart;
use Doctrine\ORM\EntityManager;
use Symfony\UX\Turbo\TurboBundle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{

    

    #[Route('/panier', name: 'app_panier')]
    public function showCart(SessionInterface $session, TagRepository $tagRepository)
    {


        $cart = $session->get('cart', []);

        return $this->render('panier/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route("/photo/add/{id}", name: "add_to_cart")]
    public function addToCart($id, Cart $cart,PhotoRepository $photoRepository,Request $request): Response

    {
        $product = $photoRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }    
        
       $carts = $cart->addToCart($product);

   
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('home/cartNB.html.twig' ,
            [
                'cartNumber' =>$carts
            ]);

        }

        return $this->redirectToRoute('app_panier');  
    }

    #[Route('/', name: 'app_requete')]
    public function requete(TagRepository $tagRepository)
    {

    $tag = $tagRepository->tags();


        return $this->render('home/tag.html.twig', [
            'tag' =>$tag
        ]);
    }


    
}
