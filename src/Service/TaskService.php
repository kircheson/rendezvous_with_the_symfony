<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Model\TaskDto;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private EntityManagerInterface $em;
    private UserService $userService;

    public function __construct(EntityManagerInterface $em, UserService $userService)
    {
        $this->em = $em;
        $this->userService = $userService;
    }

    public function create(TaskDto $taskDto): Task
    {
        $task = new Task();
        $task->setTitle($taskDto->getTitle());
        $task->setDescription($taskDto->getDescription());
        $task->setUser($this->userService->get($taskDto->getUserId()));

        $this->em->persist($task);

        return $task;
    }

    public function getAll(): array
    {
        return $this->em->getRepository(Task::class)->findAll();
    }

    public function get(int $id): ?Task
    {
        $task = $this->em->getRepository(Task::class)->find($id);

        if (!$task) {
            throw new NotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        return $task;
    }

    public function update(int $id, TaskDto $updateTaskDto): Task
    {
        $task = $this->get($id);

        if ($task->getTitle() !== $updateTaskDto->getTitle()) {
            $task->setTitle($updateTaskDto->getTitle());
        }

        if ($task->getDescription() !== $updateTaskDto->getTitle()) {
            $task->setDescription($updateTaskDto->getDescription());
        }

        if ($task->getUser()->getId() !== $updateTaskDto->getUserId()) {
            $user = $this->em->getReference(User::class, $updateTaskDto->getUserId());
            $task->setUser($user);
        }

        $this->em->persist($task);

        return $task;
    }

    public function delete(int $id): Task
    {
        $task = $this->get($id);
        $this->em->remove($task);

        return $task;
    }
}