<?php

namespace PLCTech\Domain\Entities;

class PurchaseItem
{
        private ?int $id;
        private int $purchase_id;
        private int $product_id;
        private int $quantity;
        private float $unit_price;
        private float $subtotal;
        private string $created_at;
        private ?string $updated_at;

        public function __construct(
                ?int $id,
                int $purchase_id,
                int $product_id,
                int $quantity,
                float $unit_price,
                float $subtotal,
                string $created_at = '',
                ?string $updated_at = null
        ) {
                $this->id = $id;
                $this->purchase_id = $purchase_id;
                $this->product_id = $product_id;
                $this->quantity = $quantity;
                $this->unit_price = $unit_price;
                $this->subtotal = $subtotal;
                $this->created_at = $created_at;
                $this->updated_at = $updated_at;
        }

        // * Getters...
        public function getId(): ?int
        {
                return $this->id;
        }

        public function getPurchaseId(): int
        {
                return $this->purchase_id;
        }

        public function getProductId(): int
        {
                return $this->product_id;
        }

        public function getQuantity(): int
        {
                return $this->quantity;
        }

        public function getUnitPrice(): float
        {
                return $this->unit_price;
        }

        public function getSubtotal(): float
        {
                return $this->subtotal;
        }

        public function getCreatedAt(): string
        {
                return $this->created_at;
        }

        public function getUpdatedAt(): ?string
        {
                return $this->updated_at;
        }

        // * Setters...
        public function setQuantity(float $quantity): void
        {
                $this->quantity = $quantity;
        }

        public function setUnitPrice(float $unit_price): void
        {
                $this->unit_price = $unit_price;
                $this->recalculateSubtotal();
        }

        // * Método para recalcular el subtotal...
        private function recalculateSubtotal(): void
        {
                $this->subtotal = $this->quantity * $this->unit_price;
        }
}
