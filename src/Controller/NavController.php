<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NavController extends AbstractController
{

    /**
    * @Route("/",name="accueil")
    */
    public function accueil()
    {
        $pageTittle = "Accueil";
        return $this->render("navigation/accueil.html.twig");
    }

    /**
    * @Route("/films",name="films")
    */
     public function films()
     {
        $pageTittle = "Films";

        $films = [
        "Les Dents De La mer",
        "Fast ans Furious",
        "Spiderman"
    ];
        return $this->render("navigation/films.html.twig", ["films" => $films]);
    }

        /**
    * @Route("/redirect",name="redirect")
    */
    public function homeRedirect()
    {
       return $this->redirectToRoute("accueil");
   }

}

?>