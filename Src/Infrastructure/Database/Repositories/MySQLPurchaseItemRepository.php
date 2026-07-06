<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\PurchaseItem;
use PLCTech\Domain\Repositories\PurchaseItemRepositoryInterface;
use PDO;

class MySQLPurchaseItemRepository implements PurchaseItemRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function find(int $id): ?PurchaseItem
        {
                $sql = 'SELECT * FROM purchase_items WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByPurchase(int $purchaseId): array
        {
                $sql =
                        'SELECT * FROM purchase_items 
                        WHERE purchase_id = ? ORDER BY id ASC';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$purchaseId]);
                $items = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $items[] = $this->mapToEntity($data);
                }

                return $items;
        }

        public function save(PurchaseItem $item): int
        {
                $stmt = $this->db->prepare(
                        'INSERT INTO purchase_items (purchase_id, product_id, quantity, unit_price)
                        VALUES (?, ?, ?, ?)'
                );

                $stmt->execute([
                        $item->getPurchaseId(),
                        $item->getProductId(),
                        $item->getQuantity(),
                        $item->getUnitPrice()
                ]);

                return (int) $this->db->lastInsertId();
        }

        public function saveMultiple(array $items): void
        {
                $this->db->beginTransaction();

                try {
                        foreach ($items as $item) {
                                $this->save($item);
                        }

                        $this->db->commit();
                } catch (\Exception $e) {
                        $this->db->rollBack();
                        throw $e;
                }
        }

        public function deleteByPurchase(int $purchaseId): void
        {
                $sql = 'DELETE FROM purchase_items WHERE purchase_id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$purchaseId]);
        }

        public function hasPurchaseItems(int $productId): bool
        {
                $sql = 'SELECT COUNT(*) FROM purchase_items WHERE product_id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$productId]);

                return $stmt->fetchColumn() > 0;
        }

        private function mapToEntity(array $data): PurchaseItem
        {
                return new PurchaseItem(
                        (int) ($data['id'] ?? 0),
                        (int) ($data['purchase_id'] ?? 0),
                        (int) ($data['product_id'] ?? 0),
                        (int) ($data['quantity'] ?? 0),
                        (float) ($data['unit_price'] ?? 0),
                        (float) ($data['subtotal'] ?? 0),
                        (string) ($data['created_at'] ?? ''),
                        $data['updated_at'] ?? null
                );
        }
}
