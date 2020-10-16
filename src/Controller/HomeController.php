<?php

namespace App\Controller;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $username = $this->getUser()->getUsername();
        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
            ->getToken(
                new Sha256(),
                new Key($this->getParameter('mercure_secret_key'))
            );

        $response = $this->render('home/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token,
                (new \DateTime())
                    ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );
        return $response;
    }

    /**
     * @Route("/home", name="app_home")
     */
    public function home()
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
     * @Route("/annuaire", name="app_annuary", methods="GET")
     */
     public function show_annuary(UserRepository $userRepository) :Response
     {
         if (!($this->getUser())) {
             $this->addFlash('error', 'You must logged in');
 
             return $this->redirectToRoute('app_login');
         }
         $users = $userRepository->findBy([],['createdAt' => 'DESC']);
 
         return $this->render('home/annuary.html.twig', [
             'controller_name' => 'HomeController',
             'users'    => $users,
         ]);
     }
}
