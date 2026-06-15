<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Repositories\PurchaseItemRepositoryInterface;
use PDO;

class MySQLPurchaseItemRepository implements PurchaseItemRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function hasPurchaseItems(int $productId): bool
        {
                $stmt = $this->db->prepare('SELECT COUNT(*) FROM purchase_items WHERE product_id = ?');
                $stmt->execute([$productId]);
                return $stmt->fetchColumn() > 0;
        }
}
