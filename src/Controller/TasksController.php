<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\Users;
use App\Form\TasksType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TasksController extends AbstractController
{
    /**
     * @Route("/tasks", name="tasks")
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {
        $task = new tasks();

        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);
    
        $userRepository = $this->getDoctrine()
                            ->getRepository(Users::class);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $userRepository->find($request->request->get('userId'));
            
            $task = $form->getData();
            $task->setUser($user);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }

        $tasks = $this->taskRepository = $this->getDoctrine()
        ->getRepository(tasks::class)->findAll();
        $users = $userRepository->findAll();

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasks,
            'formTasks' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/task/{id}", name="showTask")
     */

    public function update($id, Request $request, EntityManagerInterface $entityManager){

        $task = $this->getDoctrine()
                    ->getRepository(Tasks::class)->find($id);

        $UserRepository = $this->getDoctrine()
                        ->getRepository(Users::class);
        $users = $UserRepository->findAll();

        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $users = $UserRepository->find($request->request->get('userId'));
            
            $tasks = $form->getData();
            $tasks->setUser($users);

            $entityManager->persist($tasks);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }
        
        return $this->render('tasks/task.html.twig', [
            'users' => $users,
            'taskUpdate' => $form->createView(),
            'task' => $task
        ]);
        }

        /**
     * @Route("remove/{id}", name="removeTask")
     */
    public function remove($id, EntityManagerInterface $entityManager){

        $task = $this->taskRepository = $this->getDoctrine()
                        ->getRepository(Tasks::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('tasks');
    }
}
