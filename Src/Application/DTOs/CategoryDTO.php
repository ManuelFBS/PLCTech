<?php

namespace PlcTech\Application\DTOs;

class CategoryDTO
{
        public int $id;
        public string $name;
        public ?string $description;
        public string $created_at;

        public function __construct(array $data)
        {
                $this->id = (int) ($data['id'] ?? 0);
                $this->name = (string) ($data['name'] ?? '');
                $this->description = $data['description'] ?? null;
                $this->created_at = (string) ($data['created_at'] ?? '');
        }
}
