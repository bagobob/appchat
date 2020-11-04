<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoadusersController extends AbstractController
{
    /**
     * @Route("/login", name="loaduser")
     */
    public function Loaduser():response
    {
        //1- LOAD THE USERS IN THE DATABASE

        //open the json file
        $data = json_decode(file_get_contents(__DIR__.'/user_data.json'), true);
       
        foreach ($data as $data_users){
            $user = new User();
            $user->setFirstName($data_users['firstname']);
            $user->setLastName($data_users['lastname']);
            $user->setUsername($data_users['username']);
            $user->setPassword($data_users['encryptPassword']);
            $user->setStatus($data_users['statut']);

            //check if user already exists
            $AnUser = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneByUsername($user->getUsername());

            if (!($AnUser)) {  
                // 4) save the User!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                //$this->addFlash('success', 'Un utiliseur a été enregistré dans la base de données!!!!');
            }
            

        }

        
        //2- UPDATE THE DATABASE WHEN WE UPATE JSON FILE

        $data2 = json_decode(file_get_contents(__DIR__.'/user_data.json'), true);
        //get all users from the database
        $em = $this->getDoctrine()->getManager();
        $TheUsers = $em->getRepository(User::class)
                    ->findAll();

        foreach($TheUsers as $theuser){
            //search an element which is doesn't exists in the json file
            foreach($data2 as $data_users){
                if(strcmp($theuser->getUsername(), $data_users['username']) === 0 ){
                    $found = false;
                    break 1;
                }
                else{
                    $found = true;
                }
            }
            //we found this element, we remove it from the database
            if($found){
                $em->remove($theuser);
               // $this->addFlash('info', 'Un utiliseur a été supprimé dans la base de données!!!!');
            }
        } 
        $em->flush(); 

        
        return $this->render('base.html.twig', [
            'controller_name' => 'LoadusersController',]);
    }



}