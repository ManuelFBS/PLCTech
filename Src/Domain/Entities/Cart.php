<?php

namespace PLCTech\Domain\Entities;

class Cart
{
        private ?int $id;
        private int $customer_id;
        private int $product_id;
        private int $quantity;
        private string $created_at;
        private ?string $updated_at;

        public function __construct(
                ?int $id,
                int $customer_id,
                int $product_id,
                int $quantity,
                string $created_at = '',
                ?string $updated_at = null
        ) {
                $this->id = $id;
                $this->customer_id = $customer_id;
                $this->product_id = $product_id;
                $this->quantity = $quantity;
                $this->created_at = $created_at;
                $this->updated_at = $updated_at;
        }

        // * Getters...
        public function getId(): ?int
        {
                return $this->id;
        }

        public function getCustomerId(): int
        {
                return $this->customer_id;
        }

        public function getProductId(): int
        {
                return $this->product_id;
        }

        public function getQuantity(): int
        {
                return $this->quantity;
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
        public function setQuantity(int $quantity): void
        {
                $this->quantity = $quantity;
        }

        public function incrementQuantity(int $amount = 1): void
        {
                $this->quantity += $amount;
        }

        public function decrementQuantity(int $amount = 1): void
        {
                if ($this->quantity - $amount >= 0) {
                        $this->quantity -= $amount;
                }
        }
}
