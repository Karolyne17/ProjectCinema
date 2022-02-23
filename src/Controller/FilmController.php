<?php

namespace App\Controller;

use App\Entity\Film;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

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
     */
    public function createFilm(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $film = new Film;
        $film->setTitle("Les dents de la mer");
        $film->setRealisateur("Steven Spielberg");
        $film->setGenre("Horreur");

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($film);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Enregistrement du nouveau film : '.$film->getTitle());
    }



    /**
     * @Route("/filmList", name="filmList")
     */
    public function show(ManagerRegistry $doctrine)
    {
        $film = $doctrine->getManager()->getRepository(Film::class)->findAll();

        if (empty($film)) {
            throw $this->createNotFoundException(
                'Aucun film trouvé '.$id
            );
        }

        return $this->render('navigation/films.html.twig', ["films" => $film]);

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }


        /**
     * @Route("/filmUpdate", name="filmUpdate")
     */
    public function update(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setName('New product name!');
        $entityManager->flush();

        return $this->redirectToRoute('product_show', [
            'id' => $product->getId() 
        ]);
    }
}
