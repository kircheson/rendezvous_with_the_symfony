<?php

namespace App\Controller;

use App\Model\TaskDto;
use App\Service\TaskService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/create_task', name: 'task_create_get', methods: ['GET'])]
    public function createTaskGet(UserService $userService): Response
    {
        $users = $userService->getAll();

        return $this->render('task/_task_create.html.twig', ['users' => $users]);
    }

    #[Route('/create_task', name: 'task_create_post', methods: ['POST'])]
    public function createTaskPost(#[MapRequestPayload] TaskDto $createTaskDto, TaskService $taskService, UserService $userService): Response
    {
        $task = $taskService->create($createTaskDto);

        $this->em->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks', name: 'task_list')]
    public function list(TaskService $taskService): Response
    {
        $tasks = $taskService->getAll();

        return $this->render('task/_task_list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function edit(int $id, TaskService $taskService,UserService $userService): Response
    {
        $users = $userService->getAll();
        $task = $taskService->get($id);

        return $this->render('task/_task_edit.html.twig', ['task' => $task, 'users' => $users]);
    }

    #[Route('/tasks/update/{id}', name: 'task_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function update(int $id, #[MapRequestPayload] TaskDto $updateTaskDto, TaskService $taskService): Response
    {
        $task = $taskService->update($id, $updateTaskDto);

        $this->em->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/delete/{id}', name: 'task_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, TaskService $taskService): Response
    {
        $task = $taskService->delete($id);

        $this->em->flush();

        return $this->redirectToRoute('task_list');
    }
}