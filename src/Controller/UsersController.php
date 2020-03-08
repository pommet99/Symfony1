<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Tasks;
use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {
        $user = new users();
        $form = $this->createForm(UsersType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setCreatedAt(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        
        $UsersRepository = $this->getDoctrine()
        ->getRepository(Users::class)
        ->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $UsersRepository,
            'formUsers' => $form->createView()
        ]);
    }

     /**
     * @Route("/user/{id}", name="showUser")
     */

    public function update($id, Request $request, EntityManagerInterface $entityManager){

    
        $user = $this->getDoctrine()
                        ->getRepository(Users::class)->find($id);


        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            
            $users = $form->getData();

            $entityManager->persist($users);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        
        return $this->render('users/user.html.twig', [
            'user' => $user,
            'formUpdate' => $form->createView(),
        ]);
        }
}
