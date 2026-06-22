<?php

namespace PLCTech\Domain\Entities;

class Category
{
        private ?int $id;
        private string $name;
        private ?string $description;
        private string $created_at;

        public function __construct(
                ?int $id,
                string $name,
                ?string $description = null,
                string $created_at = ''
        ) {
                $this->id = $id;
                $this->name = $name;
                $this->description = $description;
                $this->created_at = $created_at;
        }

        // Getters
        public function getId(): ?int
        {
                return $this->id;
        }

        public function getName(): string
        {
                return $this->name;
        }

        public function getDescription(): ?string
        {
                return $this->description;
        }

        public function getCreatedAt(): string
        {
                return $this->created_at;
        }
}
