<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\Cart;
use PLCTech\Domain\Repositories\CartRepositoryInterface;
use PDO;

class MySQLCartRepository implements CartRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function find(int $id): ?Cart
        {
                $stmt = $this->db->prepare('SELECT * FROM carts WHERE id = ?');
                $stmt->execute([$id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByCustomer(int $customerId): array
        {
                $stmt = $this->db->prepare(
                        'SELECT c.*, p.name, p.price, p.image_prod 
                        FROM carts c 
                        JOIN products p ON c.product_id = p.id
                        WHERE c.customer_id = ?
                        ORDER BY c.created_at DESC'
                );
                $stmt->execute([$customerId]);
                $items = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $items[] = $this->mapToEntity($data);
                }

                return $items;
        }

        public function findByCustomerAndProduct(int $customerId, int $productId): ?Cart
        {
                $stmt = $this->db->prepare(
                        'SELECT * FROM carts 
                        WHERE customer_id = ? AND product_id = ?'
                );
                $stmt->execute([$customerId, $productId]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function save(Cart $cart): int
        {
                // > Verificar si ya existe...
                $existing = $this->findByCustomerAndProduct(
                        $cart->getCustomerId(),
                        $cart->getProductId()
                );

                if ($existing) {
                        // ? Actualizar cantidad...
                        $newQuantity = $existing->getQuantity() + $cart->getQuantity();
                        $stmt = $this->db->prepare(
                                'UPDATE carts 
                                SET quantity = ?, updated_at = CURRENT_TIMESTAMP
                                WHERE customer_id = ? AND product_id = ?'
                        );
                        $stmt->execute([
                                $newQuantity,
                                $cart->getCustomerId(),
                                $cart->getProductId()
                        ]);
                        return $existing->getId();
                }

                // Insertar nuevo
                $stmt = $this->db->prepare('
            INSERT INTO carts (customer_id, product_id, quantity)
            VALUES (?, ?, ?)
        ');

                $stmt->execute([
                        $cart->getCustomerId(),
                        $cart->getProductId(),
                        $cart->getQuantity()
                ]);

                return (int) $this->db->lastInsertId();
        }

        public function update(Cart $cart): void
        {
                $stmt = $this->db->prepare(
                        'UPDATE carts 
                        SET quantity = ?, updated_at = CURRENT_TIMESTAMP
                        WHERE id = ?'
                );

                $stmt->execute([
                        $cart->getQuantity(),
                        $cart->getId()
                ]);
        }

        public function delete(int $id): void
        {
                $stmt = $this->db->prepare('DELETE FROM carts WHERE id = ?');
                $stmt->execute([$id]);
        }

        public function clearByCustomer(int $customerId): void
        {
                $stmt = $this->db->prepare('DELETE FROM carts WHERE customer_id = ?');
                $stmt->execute([$customerId]);
        }

        public function deleteByCustomerAndProduct(int $customerId, int $productId): void
        {
                $stmt = $this->db->prepare(
                        'DELETE FROM carts 
                        WHERE customer_id = ? AND product_id = ?'
                );
                $stmt->execute([$customerId, $productId]);
        }

        private function mapToEntity(array $data): Cart
        {
                return new Cart(
                        (int) ($data['id'] ?? 0),
                        (int) ($data['customer_id'] ?? 0),
                        (int) ($data['product_id'] ?? 0),
                        (int) ($data['quantity'] ?? 0),
                        (string) ($data['created_at'] ?? ''),
                        $data['updated_at'] ?? null
                );
        }
}
