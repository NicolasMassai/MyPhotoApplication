<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\Customer;
use App\Entity\OrderItem;
use App\Repository\UserRepository;
use App\Repository\PhotoRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private CustomerRepository $customerRepository;
        private $security;

    public function __construct(CustomerRepository $customerRepository, Security $security)
    {
        $this->customerRepository = $customerRepository;
        $this->security = $security;

    }


    #[Route('/commande', name: 'app_commande')]
    public function validateOrder(EntityManagerInterface $em,SessionInterface $session, PhotoRepository $photorepository,Request $request): Response
    {
        $panier = $session->get('cart', []);


        $commande = new Order();

        $user = $this->getUser();
    
        //$customer = $em->getRepository(Customer::class)->findOneBy(['user' => $user]);

        //dd($user);
        //$commande->setCustomer($user->getCustomer());

        foreach ($panier as $id=>$item) {
            $commandeDetails = new OrderItem();

            $photo = $photorepository->find($id);

            $price = $photo->getPrice();

            $commandeDetails->setPhoto($photo);
            $commandeDetails->setPrix($price);
            $commandeDetails->setQuantity($item['qty']);


            $commande->addOrderItem($commandeDetails);
        }
        

        $commande->setStatus('Validé');
        $em->persist($commande);
        $em->flush();

        $session->remove('cart');

        return $this->redirectToRoute('app_home');



    }
}
