<?php

namespace App\Domains\Workflows\DTOs;

readonly class WorkflowData
{
    public function __construct(
        public string $name,
        public ?string $description,
    ) {}

    // Một phương thức static để tạo DTO từ Request hoặc Array
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name']
        );
    }
}