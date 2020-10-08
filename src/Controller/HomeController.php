<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index()
    {
        if (!($this->getUser())) {
            $this->addFlash('error', 'You must logged in');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/profil", name="app_profil")
     */
    public function show_profile()
    {
        if (!($this->getUser())) {
            $this->addFlash('error', 'You must logged in');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('home/profile.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/annuaire", name="app_annuary")
     */
     public function show_annuary()
     {
         if (!($this->getUser())) {
             $this->addFlash('error', 'You must logged in');
 
             return $this->redirectToRoute('app_login');
         }
 
         return $this->render('home/annuary.html.twig', [
             'controller_name' => 'HomeController',
         ]);
     }
}
