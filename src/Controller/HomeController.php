<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\UX\Turbo\TurboBundle;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{



    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [
            'photos' => 'photo',
        ]);
    }

    #[Route('/photo', name: 'app_photo')]
    public function photo(PhotoRepository $photoRepository): Response
    {

        $photo = $photoRepository->findAll();
        return $this->render('home/photo.html.twig', [
            'photos' => $photo,
        ]);
    }
    
    #[Route('/photo/{slug}', name: 'app_display_photo')]
    public function displayPhoto(Photo $photo, PhotoRepository $photoRepository, string $slug): Response
    {

        $photo = $photoRepository->findOneBySlug($slug);

//        $photo = $photoRepository->find($id);

            return $this->render('home/detail.html.twig', [
            'photo' => $photo,
        ]);
    }

        

    

    #[Route('/test', name: "add_test")]
    public function addTest(Request $request): Response
    {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
             return $this->render('home/cartNB.html.twig' ,['cartNumber' => 5]);
        
    }
}
