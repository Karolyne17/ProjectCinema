<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
    {
        /**
         * @Route("/profil/{prenom?Caroline}", name="profil")
         */

        function profil($prenom)
        {
            dd($prenom);
        }

        /**
         * @Route("/salle/{numSalle?17}", name="salle", requirements={"numSalle"="\d+"})
         */

        function salle($numSalle)
        {
            return $this->render('default/index.html.twig', ['numSalle' => $numSalle]);
        }


    }
           
    