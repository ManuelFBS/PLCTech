<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\Purchase;
use PLCTech\Domain\Repositories\PurchaseRepositoryInterface;
use PDO;

class MySQLPurchaseRepository implements PurchaseRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function find(int $id): ?Purchase
        {
                $sql = 'SELECT * FROM purchases WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByInvoiceNumber(string $invoiceNumber): ?Purchase
        {
                $sql = 'SELECT * FROM purchases WHERE invoice_number = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$invoiceNumber]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByCustomer(int $customerId): array
        {
                $sql = 'SELECT * FROM purchases 
                        WHERE customer_id = ? 
                        ORDER BY purchase_date DESC';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$customerId]);
                $purchases = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $purchases[] = $this->mapToEntity($data);
                }

                return $purchases;
        }

        public function findByDateRange(string $startDate, string $endDate): array
        {
                $sql =
                        'SELECT * FROM purchases 
                        WHERE purchase_date BETWEEN ? AND ? 
                        ORDER BY purchase_date DESC';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$startDate, $endDate]);
                $purchases = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $purchases[] = $this->mapToEntity($data);
                };

                return $purchases;
        }

        public function findAll(): array
        {
                $sql = 'SELECT * FROM purchases ORDER BY purchase_date DESC';
                $stmt = $this->db->query($sql);
                $purchases = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $purchases[] = $this->mapToEntity($data);
                }

                return $purchases;
        }

        public function save(Purchase $purchase): int
        {
                $sql =
                        'INSERT INTO purchases (
                                invoice_number, customer_id, user_id, purchase_date,
                                subtotal, tax, total_amount, payment_method,
                                payment_status, is_online, status, notes
                                ) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                        $purchase->getInvoiceNumber(),
                        $purchase->getCustomerId(),
                        $purchase->getUserId(),
                        $purchase->getPurchaseDate(),
                        $purchase->getSubtotal(),
                        $purchase->getTax(),
                        $purchase->getTotalAmount(),
                        $purchase->getPaymentMethod(),
                        $purchase->getPaymentStatus(),
                        $purchase->isOnline() ? 1 : 0,
                        $purchase->getStatus(),
                        $purchase->getNotes()
                ]);

                return (int) $this->db->lastInsertId();
        }

        public function update(Purchase $purchase): void
        {
                $stmt = $this->db->prepare(
                        'UPDATE purchases 
                                SET invoice_number = ?, customer_id = ?, user_id = ?,
                                purchase_date = ?, subtotal = ?, tax = ?,
                                total_amount = ?, payment_method = ?,
                                payment_status = ?, is_online = ?, status = ?, notes = ?
                                WHERE id = ?'
                );

                $stmt->execute([
                        $purchase->getInvoiceNumber(),
                        $purchase->getCustomerId(),
                        $purchase->getUserId(),
                        $purchase->getPurchaseDate(),
                        $purchase->getSubtotal(),
                        $purchase->getTax(),
                        $purchase->getTotalAmount(),
                        $purchase->getPaymentMethod(),
                        $purchase->getPaymentStatus(),
                        $purchase->isOnline() ? 1 : 0,
                        $purchase->getStatus(),
                        $purchase->getNotes(),
                        $purchase->getId()
                ]);
        }

        public function updateStatus(int $id, string $status): void
        {
                $stmt = $this->db->prepare('UPDATE purchases SET status = ? WHERE id = ?');
                $stmt->execute([$status, $id]);
        }

        public function updatePaymentStatus(int $id, string $paymentStatus): void
        {
                $stmt = $this->db->prepare('UPDATE purchases SET payment_status = ? WHERE id = ?');
                $stmt->execute([$paymentStatus, $id]);
        }

        public function delete(int $id): void
        {
                $stmt = $this->db->prepare('DELETE FROM purchases WHERE id = ?');
                $stmt->execute([$id]);
        }

        private function mapToEntity(array $data): Purchase
        {
                return new Purchase(
                        (int) ($data['id']) ?? 0,
                        (string) ($data['invoice_number']) ?? '',
                        (int) ($data['customer_id']) ?? 0,
                        $data['user_id'] ? (int) ($data['user_id']) : null,
                        (string) ($data['purchase_date'] ?? date('Y-m-d H:i:s')),
                        (float) ($data['subtotal']) ?? 0,
                        (float) ($data['tax']) ?? 0,
                        (float) ($data['total_amount']) ?? 0,
                        (string) ($data['payment_method']) ?? null,
                        (string) ($data['payment_status']) ?? 'pending',
                        (bool) ($data['payment_status']) ?? false,
                        (string) ($data['status']) ?? 'active',
                        $data['notes'] ?? null,
                        (string) ($data['created_at']) ?? '',
                        $data['updated_at'] ?? null
                );
        }
}
