<?php
namespace App\Service;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{   

    private const CART_SESSION = 'cart2';

    private array $cart;
    private SessionInterface $session;


    public function __construct(private RequestStack $requestStack)
    
    {
        $this->session=$requestStack->getSession();
        $this->cart=$this->session->get(self::CART_SESSION, []);;
  
    }
   
    public function addToCart($product)
    {
           
        
        $cart = $this->session->get('cart', []);
        $cart[] = $product;
        $this->session->set('cart', $cart);

             return count($cart);

    }
  
  

}