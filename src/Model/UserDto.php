<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\UserPassword]
        #[Assert\NotCompromisedPassword]
        #[Assert\PasswordStrength]
        #[Assert\Length(max: 255)]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[Assert\Email(['message' => 'Email "{{ value }}" некорректный'])]
        public string $email,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}