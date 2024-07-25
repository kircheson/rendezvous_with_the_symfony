<?php

namespace App\Service;

use App\Entity\User;
use App\Model\UserDto;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(UserDto $userDto): User
    {
        $user = new User();
        $user->setName($userDto->name);
        $user->setPassword($userDto->password);
        $user->setEmail($userDto->email);

        $this->em->persist($user);

        return $user;
    }

    public function getAll(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    public function get(int $id): ?User
    {
        $user = $this->em->getRepository(User::class)->find($id);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь с ID ' . $id . ' не найден');
        }

        return $user;
    }

    public function update(int $id, UserDto $updateUserDto): User
    {
        $user = $this->get($id);

        if ($user->getName() !== $updateUserDto->name) {
            $user->setName($updateUserDto->name);
        }

        if ($user->getPassword() !== $updateUserDto->password) {
            $user->setPassword($updateUserDto->password);
        }

        if ($user->getEmail() !== $updateUserDto->email) {
            $user->setEmail($updateUserDto->email);
        }

        $this->em->persist($user);

        return $user;
    }

    public function delete(int $id): User
    {
        $user = $this->get($id);
        $this->em->remove($user);

        return $user;
    }

    public function find(?int $userId): ?User
    {
        if ($userId === null) {
            return null;
        }

        return $this->em->getRepository(User::class)->find($userId);
    }

}