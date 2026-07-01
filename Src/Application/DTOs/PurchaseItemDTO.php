<?php

namespace PLCTech\Application\DTOs;

class PurchaseItemDTO
{
        public ?int $id;
        public int $purchase_id;
        public int $product_id;
        public int $quantity;
        public float $unit_price;
        public float $subtotal;
        public string $created_at;
        public ?string $updated_at;

        public function __construct(array $data = [])
        {
                $this->id = $data['id'] ?? null;
                $this->purchase_id = $data['purchase_id'] ?? 0;
                $this->product_id = $data['product_id'] ?? 0;
                $this->quantity = (int) ($data['quantity'] ?? 0);
                $this->unit_price = (float) ($data['unit_price'] ?? 0);
                $this->subtotal = (float) ($data['subtotal'] ?? ($this->quantity * $this->unit_price));
                $this->created_at = $data['created_at'] ?? '';
                $this->updated_at = $data['updated_at'] ?? null;
        }
}
