<?php

namespace App\Controller;

use App\Entity\Film;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class FilmController extends AbstractController
{
    #[Route('/film', name: 'film')]
    public function index(): Response
    {
        return $this->render('film/index.html.twig', [
            'controller_name' => 'FilmController',
        ]);
    }



    /**
     * @Route("/filmCreate", name="filmCreate")
     * @Route("/filmUpdate/{id}", name="filmUpdate")
     */
    public function addFilm(Request $request, ManagerRegistry $doctrine, $id = null)
    {
       $entityManager = $doctrine->getManager();

       $isEditor = false;

        if(isset($id))
        {
            $film = $doctrine->getRepository(Film::class)->find($id);
            if(!isset($id))
            { 
                return $this->redirectToRoute('filmList');
            }
            $isEditor = true;
        }
        else
        {
            $film = new Film;
        }
     
        $form = $this->createFormBuilder($film)
            ->add("title", TextType::class, ['label' => 'Titre : '])
            ->add("realisateur", TextType::class, ['label' => 'Réalisateur : '])
            ->add("genre", TextType::class, ['label' => 'Genre : '])
            ->add("save", SubmitType::class, ['label' => 'Créer le film'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            
            $film = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($film);
            $entityManager->flush();

    
            // ... perform some action, such as saving the task to the database
    
            return $this->redirectToRoute('filmList');
        }

        return $this->render('film/create.html.twig', [
            'form' => $form->createView(),
            'isEditor'=> $isEditor
        ]);
        
    }


    /**
     * @Route("/filmDelete/{id}", name="filmDelete")
     */
    public function delete(ManagerRegistry $doctrine, $id)
    {

        $entityManager = $doctrine->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        if(isset($film))
        {
            $entityManager->remove($film);
            $entityManager->flush();

        }
            return $this->redirectToRoute('filmList');
           
    }


    /**
     * @Route("/filmList", name="filmList")
     */
    public function show(ManagerRegistry $doctrine)
    {

        $films = $doctrine->getManager()->getRepository(Film::class)->findAll();

        return $this->render('film/listing.html.twig', ["films" => $films]);

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }


    /**
     * @Route("/filmDetail/{id}", name="filmDetail")
     */

    public function detail(ManagerRegistry $doctrine, $id)
    {

        $entityManager = $doctrine->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        if(!isset($id))
        { 
            return $this->redirectToRoute('filmList');
            // return $this->redirectToRoute('filmList', ["message" => "Erreur aucun film trouvé"]);
        }

        return $this->render('film/detail.html.twig', ["film" => $film]);

       

    }


}
