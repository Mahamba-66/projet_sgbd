<?php

namespace App\Services;

class ValidationResult
{
    private $validVoters;
    private $errors;

    public function __construct(array $validVoters, array $errors)
    {
        $this->validVoters = $validVoters;
        $this->errors = $errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function getValidVoters(): array
    {
        return $this->validVoters;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getTotalCount(): int
    {
        return count($this->validVoters) + count($this->errors);
    }
}
