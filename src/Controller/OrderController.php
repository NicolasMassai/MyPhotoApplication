<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/commande', name: 'app_order')]
    public function index(SessionInterface $session,EntityManagerInterface $em,UserRepository $userRepository): Response
    {
        $cart = $session->get('cart', []);

        //$user = $this->userRepository->find($this->getUser());

        $commande = new Order();
           //$commande->setCustomer($user); 
           $commande->setCreatedAt(new DateTimeImmutable('now')); 
   
           $em->persist($commande);
           $em->flush();

        return $this->render('order/index.html.twig', [
            'cart' => $cart,
        ]);
    }
}
