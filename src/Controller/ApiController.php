<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTEncodedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/', name: 'app_api')]
    public function index(): Response
    {
        return $this->json(['message'=>'Bienvenue !']);
    }

    #[Route('/token', name: 'app_token',methods:['POST'])]
    public function getTokenJWT(UserRepository $userRepository, Request $request, UserPasswordHasher $userPasswordHasher, JWTEncoderInterface $jWTEncoderInterface): Response
    {

        $user = $userRepository->findOneBy(['email'=>$request->get('email')]);

        if($user !== null){
            if($userPasswordHasher->isPasswordValid($user,$request->get('password'))){
                return $this->json(['token'=>$jWTEncoderInterface->encode([
                    'username'=>$user->getUserIdentifier(),
                    'exp' => time() + 30
                    ])]);

            }
        }
        throw new BadCredentialsException();
    }
}
