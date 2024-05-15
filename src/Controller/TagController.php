<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Photo;
use App\Form\TagType;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{

    #[Route('/tag/{slug}', name: 'app_display_tag')]
    public function displayTag(Tag $tag): Response
    {
        return $this->render('home/slug.html.twig', [
            'tag' => $tag
        ]);
    }

   

    #[Route('/search/tag', name: 'app_search_tag')]
    public function searchTag(Request $request): Response
    {

        $form = $this->createForm(TagType::class,null,[
            'action'=>$this->generateUrl('app_search_tag')
        ]);
        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()){
            $tag = $form->get("tag")->getData();

            return $this->redirectToRoute('app_display_photo',['slug' => $tag->getSlug()]);
        }

    

        return $this->render('tag/index.html.twig', [
            'form' => $form,
        ]);
    }
}
