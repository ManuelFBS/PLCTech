<?php

namespace PLCTech\Domain\Entities;

class Product
{
        private ?int $id;
        private string $name;
        private ?string $description;
        private ?string $image_prod;
        private float $price;
        private int $stock;
        private bool $is_active;
        private string $created_at;
        private ?string $updated_at;

        public function __construct(
                ?int $id,
                string $name,
                ?string $description,
                ?string $image_prod,
                float $price,
                int $stock,
                bool $is_active = true,
                string $created_at = '',
                ?string $updated_at = null
        ) {
                $this->id = $id;
                $this->name = $name;
                $this->description = $description;
                $this->image_prod = $image_prod;
                $this->price = $price;
                $this->stock = $stock;
                $this->is_active = $is_active;
                $this->created_at = $created_at;
                $this->updated_at = $updated_at;
        }

        // * Getters...
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

        public function getImageProd(): ?string
        {
                return $this->image_prod;
        }

        public function getPrice(): float
        {
                return $this->price;
        }

        public function getStock(): int
        {
                return $this->stock;
        }

        public function isActive(): bool
        {
                return $this->is_active;
        }

        public function getCreatedAt(): string
        {
                return $this->created_at;
        }

        public function getUpdatedAt(): ?string
        {
                return $this->updated_at;
        }

        // * Setters...
        public function setStock(int $stock): void
        {
                $this->stock = $stock;
        }

        public function setIsActive(bool $is_active): void
        {
                $this->is_active = $is_active;
        }

        // * Métodos útiles...
        public function isLowStock(): bool
        {
                return $this->stock <= 5 && $this->stock > 0;
        }

        public function isOutOfStock(): bool
        {
                return $this->stock <= 0;
        }

        public function isDiscontinued(): bool
        {
                return !$this->is_active;
        }
}
