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
        return $this->redirectToRoute('filmList');
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