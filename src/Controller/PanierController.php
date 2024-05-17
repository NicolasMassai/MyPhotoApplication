<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use App\Repository\TagRepository;
use App\Service\Cart;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\HttpFoundation\Request;
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
        $cartTotal = $this->calculTotalCart($cart);

        return $this->render('panier/index.html.twig', [
            'cart' => $cart,
            'total' => $cartTotal['total'],
        ]);
    }

    public function calculTotalCart(array $items)
    {
        $cartTotal = ['total' => 0, 'qty' => 0];
        foreach ($items as $item) {
           
            $cartTotal['total']     += $item['price'] * $item['qty'];
        }
        return $cartTotal;
    }
    #[Route("/photo/add/{id}", name: "add_to_cart")]
    public function addToCart($id, Cart $cart,PhotoRepository $photoRepository,Request $request,SessionInterface $session): Response

    {

        $photo = $photoRepository->find($id);
        $cart = $session->get('cart', []);

      

        if (isset($cart[$id]) && isset($cart[$id]['qty']))
            $cart[$id]['qty'] += $request->request->get('qty', 0);
        else {
            $cart[$id]['url'] = $photo->getUrl();
            $cart[$id]['qty'] = $request->request->get('qty', 0);
            $cart[$id]['title'] = $photo->getTitle();
            $cart[$id]['slug'] = $photo->getSlug();
            $cart[$id]['price'] = $photo->getPrice();
        }

        $session->set('cart', $cart);

   
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('home/cartNB.html.twig' ,
            [
                'cartNumber' =>array_reduce($cart,fn($qty,$item)=>$qty+$item['qty'],0),
            ]);

        }

        return $this->redirectToRoute('app_panier');  
    }

    #[Route('/panier/update/{id}', name: 'app_panier_update')]
    public function changeQuantity(SessionInterface $session, Request $request,$id=null)
    {
        $cart = $session->get('cart', []);
        $qty = $request->request->get('qty', null);
        if ($id !== null && isset($cart[$id]) && $qty !== null) {
            if($qty == 0)
                unset($cart[$id]);
            else
                $cart[$id]['qty'] = $qty;
        }

        $session->set('cart', $cart);

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
