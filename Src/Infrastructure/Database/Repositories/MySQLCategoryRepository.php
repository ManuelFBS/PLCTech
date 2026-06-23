<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\Category;
use PLCTech\Domain\Repositories\CategoryRepositoryInterface;
use PDO;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function find(int $id): ?Category
        {
                $sql = 'SELECT * FROM categories WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByName(string $name): ?Category
        {
                $sql = 'SELECT * FROM categories WHERE name = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$name]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findAll(): array
        {
                $sql = 'SELECT * FROM categories ORDER BY name ASC';
                $stmt = $this->db->prepare($sql);
                $categories = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $categories[] = $this->mapToEntity($data);
                }

                return $categories;
        }

        public function save(Category $category): int
        {
                $sql = 'INSERT INTO categories (name, description) VALUES (?, ?)';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                        $category->getName(),
                        $category->getDescription()
                ]);

                return (int) $this->db->lastInsertId();
        }

        public function update(Category $category): void
        {
                $stmt = $this->db->prepare('UPDATE categories SET name = ?, description = ? WHERE id = ?');
                $stmt->execute([
                        $category->getName(),
                        $category->getDescription(),
                        $category->getId()
                ]);
        }

        public function delete(int $id): void
        {
                $stmt = $this->db->prepare('DELETE FROM categories WHERE id = ?');
                $stmt->execute([$id]);
        }

        private function mapToEntity(array $data): Category
        {
                return new Category(
                        (int) ($data['id'] ?? 0),
                        (string) ($data['name'] ?? ''),
                        $data['description'] ?? null,
                        (string) ($data['created_At'] ?? '')
                );
        }
}
