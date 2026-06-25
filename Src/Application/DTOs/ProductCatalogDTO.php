<?php

namespace PLCTech\Application\DTOs;

class ProductCatalogDTO
{
        public int $id;
        public string $name;
        public ?string $description;
        public ?int $category_id;
        public ?string $category_name;  // ← Este campo solo existe en el DTO, no en la entidad
        public ?string $image_prod;
        public float $price;
        public int $stock;
        public bool $is_active;
        public string $created_at;
        public ?string $updated_at;

        public function __construct(array $data)
        {
                $this->id = (int) ($data['id'] ?? 0);
                $this->name = (string) ($data['name'] ?? '');
                $this->description = $data['description'] ?? null;
                $this->category_id = $data['category_id'] ? (int) $data['category_id'] : null;
                $this->category_name = $data['category_name'] ?? null;  // ← Viene del JOIN
                $this->image_prod = $data['image_prod'] ?? null;
                $this->price = (float) ($data['price'] ?? 0);
                $this->stock = (int) ($data['stock'] ?? 0);
                $this->is_active = (bool) ($data['is_active'] ?? true);
                $this->created_at = (string) ($data['created_at'] ?? '');
                $this->updated_at = $data['updated_at'] ?? null;
        }

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

        public function getImageUrl(): string
        {
                if ($this->image_prod) {
                        return $_ENV['APP_URL'] . '/uploads/products/' . $this->image_prod;
                }
                return $_ENV['APP_URL'] . '/assets/images/no-image.png';
        }
}
