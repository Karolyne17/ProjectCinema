<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Seance;
use App\Form\FilmCreateType;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
    public function addFilm(Request $request, ManagerRegistry $doctrine, Film $film = null, ValidatorInterface $validator, SluggerInterface $slugger )
    {
       $entityManager = $doctrine->getManager();

        if(!$film)
        {
            $film = new Film;
        }
     
        $form = $this->createForm(FilmCreateType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            
            if(!$film->getId())
            {
                $film->setCreatedAt(new \DateTime('now'));
            }

            $film->setUpdatedAt(new \DateTime('now'));

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'image' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                }
                catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
                $film->setImage($newFilename);
            }

            $film = $form->getData();

            $entityManager->persist($film);
            $entityManager->flush();

    
            // ... perform some action, such as saving the task to the database
    
            return $this->redirectToRoute('filmList');
        }

        $errors = $validator->validate($film);

        return $this->render('film/create.html.twig', [
            'form' => $form->createView(),
            'isEditor'=> $film->getId(),
            'errors' => $errors
        ]);
        
    }


    /**
     * @Route("/filmDelete/{id}", name="filmDelete")
     */
    public function delete(ManagerRegistry $doctrine, Film $film, SeanceRepository $seance )
    {

        $entityManager = $doctrine->getManager();

        $seanceFilms = $seance -> findBy(["film"=>$film]);

        if(isset($film))
        {
            if(isset($seanceFilms)){
                foreach($seanceFilms as $seanceFilm){
                    $entityManager->remove($seanceFilm);
                    $entityManager->flush();
                }
                
            }
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

    }


    /**
     * @Route("/filmDetail/{id}", name="filmDetail")
     */

    public function detail(ManagerRegistry $doctrine, $id, SeanceRepository $seance)
    {

        $entityManager = $doctrine->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);
        $seance = $entityManager->getRepository(Seance::class)->find($id);

        if(!isset($id))
        { 
            return $this->redirectToRoute('filmList');
            // return $this->redirectToRoute('filmList', ["message" => "Erreur aucun film trouvé"]);
        }

        return $this->render('film/detail.html.twig', ["film" => $film, "seance" =>$seance]);

       

    }


}
