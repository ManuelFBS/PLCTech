<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\Purchase;

interface PurchaseRepositoryInterface
{
        public function find(int $id): ?Purchase;
        public function findByInvoiceNumber(string $invoiceNumber): ?Purchase;
        public function findByCustomer(int $customerId): array;
        public function findByDateRange(string $startDate, string $endDate): array;
        public function findAll(): array;
        public function save(Purchase $purchase): int;
        public function update(Purchase $purchase): void;
        public function updateStatus(int $id, string $status): void;
        public function updatePaymentStatus(int $id, string $paymentStatus): void;
        public function delete(int $id): void;
}
