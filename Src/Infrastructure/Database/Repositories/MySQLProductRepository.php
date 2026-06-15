<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\Product;
use PLCTech\Domain\Repositories\ProductRepositoryInterface;
use PDO;

class MySQLProductRepository implements ProductRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function find(int $id): ?Product
        {
                $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ?');
                $stmt->execute([$id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByName(string $name): ?Product
        {
                $stmt = $this->db->prepare('SELECT * FROM products WHERE name = ?');
                $stmt->execute([$name]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findAll(): array
        {
                $stmt = $this->db->query('SELECT * FROM products ORDER BY id DESC');
                $products = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $products[] = $this->mapToEntity($data);
                }

                return $products;
        }

        public function findActive(): array
        {
                $stmt = $this->db->prepare('SELECT * FROM products WHERE is_active = 1 ORDER BY name ASC');
                $stmt->execute();
                $products = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $products[] = $this->mapToEntity($data);
                }

                return $products;
        }

        public function save(Product $product): int
        {
                $sql = 'INSERT INTO products (name, description, image_prod, price, stock, is_active)
                        VALUES (?, ?, ?, ?, ?, ?)';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                        $product->getName(),
                        $product->getDescription(),
                        $product->getImageProd(),
                        $product->getPrice(),
                        $product->getStock(),
                        $product->isActive() ? 1 : 0
                ]);

                return (int) $this->db->lastInsertId();
        }

        public function update(Product $product): void
        {
                $sql = 'UPDATE products 
                        SET name = ?, description = ?, image_prod = ?, price = ?, stock = ?, is_active = ?
                        WHERE id = ?';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                        $product->getName(),
                        $product->getDescription(),
                        $product->getImageProd(),
                        $product->getPrice(),
                        $product->getStock(),
                        $product->isActive() ? 1 : 0,
                        $product->getId()
                ]);
        }

        public function delete(int $id): void
        {
                $sql = 'DELETE FROM products WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
        }

        public function updateStock(int $id, int $quantity): void
        {
                $sql = 'UPDATE products SET stock = stock - ? WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$quantity, $id]);
        }

        private function mapToEntity(array $data): Product
        {
                return new Product(
                        (int) ($data['id'] ?? 0),
                        (string) ($data['name'] ?? ''),
                        $data['description'] ?? null,
                        $data['image_prod'] ?? null,
                        (float) ($data['price'] ?? 0),
                        (int) ($data['stock'] ?? 0),
                        (bool) ($data['is_active'] ?? true),
                        (string) ($data['created_at'] ?? ''),
                        $data['updated_at'] ?? null
                );
        }
}
