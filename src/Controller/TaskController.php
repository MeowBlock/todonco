<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_list', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy([], ['isDone' => 'ASC', 'createdAt' => 'DESC']),
        ]);
    }

    #[Route('/done', name: 'task_finished_list', methods: ['GET'])]
    public function finished(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findby(['isDone' => '1']),
        ]);
    }

    #[Route('/create', name: 'task_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setAuthor($this->getUser());
            $task->setIsDone(false);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    // public function show(Task $task): Response
    // {
    //     return $this->render('task/show.html.twig', [
    //         'task' => $task,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {


        if($task->getAuthor() != $this->getUser() && !$this->getUser()->isAdmin()){
            throw new BadRequestHttpException('Vous n\'etes pas propriétaire de la tache', null, 418);
            //return $this->redirectToRoute('task_list', [RESPONSE::HTTP_FORBIDDEN]);
        }
        if(!$this->getUser()->isAdmin() && $task->getAuthor() == NULL){
            throw new BadRequestHttpException('Seuls les administrateurs peuvent modifier une tache sans propriétaire', null, 418);
            //return $this->redirectToRoute('task_list', [RESPONSE::HTTP_FORBIDDEN]);
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_list');
    }
}
