<?php

namespace PLCTech\Domain\Repositories;

interface PurchaseItemRepositoryInterface
{
        public function hasPurchaseItems(int $productId): bool;
}
