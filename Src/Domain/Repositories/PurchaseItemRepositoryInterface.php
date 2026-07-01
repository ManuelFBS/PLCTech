<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\PurchaseItem;

interface PurchaseItemRepositoryInterface
{
        public function find(int $id): ?PurchaseItem;
        public function findByPurchase(int $purchaseId): array;
        public function save(PurchaseItem $item): int;
        public function saveMultiple(array $items): void;
        public function deleteByPurchase(int $purchaseId): void;
        public function hasPurchaseItems(int $productId): bool;
}
