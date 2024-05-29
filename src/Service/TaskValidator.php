<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class TaskValidator
{
    private const MAX_TITLE_LENGTH = 255;
    private const MAX_DESCRIPTION_LENGTH = 255;
    private const MAX_EMAIL_LENGTH = 255;

    private $maxLengths = [
        'title' => self::MAX_TITLE_LENGTH,
        'description' => self::MAX_DESCRIPTION_LENGTH,
        'email' => self::MAX_EMAIL_LENGTH,
    ];

    private $title;
    private $description;
    private $email;
    private $isValid = true;
    private $errors = [];

    public function __construct(RequestStack $requestStack)
    {
        $this->fetchDataFromRequest($requestStack);
        $this->validateData();
    }

    public function fetchDataFromRequest(RequestStack $requestStack): void
    {
        $request = $requestStack->getCurrentRequest();
        if ($request) {
            $this->title = (string)$request->request->get('title');
            $this->description = (string)$request->request->get('description');
            $this->email = (string)$request->request->get('email');

            if (!preg_match('/^[a-zA-Z\s]+$/', $this->title)) {
                $this->isValid = false;
                $this->errors['title'] = 'Заголовок должен состоять только из букв и пробелов';
            }
            if (!is_string($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->isValid = false;
                $this->errors['email'] = 'Email должен быть строкой и иметь правильный формат';
            }
            if (!preg_match('/^.+$/', $this->description)) {
                $this->description = ''; // Если description пустой, сохраняем в БД пустую строку
            }
        }
    }

    private function validateData(): void
    {
        $this->validateField('title', $this->title, ['required', 'string', 'max_length']);
        $this->validateField('description', $this->description, ['required', 'string', 'max_length']);
        $this->validateField('email', $this->email, ['required', 'string', 'max_length', 'email']);
    }

    private function validateField(string $fieldName, $value): void
    {
        if (strlen($value) > $this->maxLengths[$fieldName]) {
            $this->isValid = false;
            $this->errors[$fieldName] = sprintf('Поле %s не должно превышать %d символов.', $fieldName, $this->maxLengths[$fieldName]);
        }
    }

    private function getMaxLengthForField(string $fieldName): int
    {
        return $this->maxLengths[$fieldName] ?? 0;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEmail(): string
    {
        return $this->email;

    }
}
