<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\Product;

interface ProductRepositoryInterface
{
        public function find(int $id): ?Product;
        public function findByName(string $name): ?Product;
        public function findAll(): array;
        public function findActive(): array;
        public function save(Product $product): int;
        public function update(Product $product): void;
        public function delete(int $id): void;
        public function updateStock(int $id, int $quantity): void;
}
