<?php

namespace PLCTech\Application\DTOs;

class CartDTO
{
        public ?int $id;
        public int $customer_id;
        public int $product_id;
        public int $quantity;
        public string $created_at;
        public ?string $updated_at;
        // * Campos adicionales para mostrar en el carrito (no están en la tabla)...
        public ?string $product_name;
        public ?float $product_price;
        public ?string $product_image;
        public ?float $subtotal;

        public function __construct(array $data = [])
        {
                $this->id = $data['id'] ?? null;
                $this->customer_id = (int) ($data['customer_id'] ?? 0);
                $this->product_id = (int) ($data['product_id'] ?? 0);
                $this->quantity = (int) ($data['quantity'] ?? 0);
                $this->created_at = $data['created_at'] ?? '';
                $this->updated_at = $data['updated_at'] ?? null;

                // > Campos adicionales (para mostrar en la vista)...
                $this->product_name = $data['product_name'] ?? null;
                $this->product_price = isset($data['product_price']) ? (float) $data['product_price'] : null;
                $this->product_image = $data['product_image'] ?? null;
                $this->subtotal = isset($this->product_price) ? $this->quantity * $this->product_price : null;
        }

        /**
         * * Calcula el subtotal del item del carrito...
         */
        public function getSubtotal(): ?float
        {
                if ($this->product_price !== null) {
                        return $this->quantity * $this->product_price;
                }
                return null;
        }

        /**
         * * Verifica si el item tiene todos los datos del producto...
         */
        public function hasProductData(): bool
        {
                return $this->product_name !== null && $this->product_price !== null;
        }
}
