<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\Category;

interface CategoryRepositoryInterface
{
        public function find(int $id): ?Category;
        public function findByName(string $name): ?Category;
        public function findAll(): array;
        public function save(Category $category): int;
        public function update(Category $category): void;
        public function delete(int $id): void;
}
