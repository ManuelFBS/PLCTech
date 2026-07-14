<?php

namespace PLCTech\Application\DTOs;

class PurchaseDTO
{
        public ?int $id;
        public string $invoice_number;
        public int $customer_id;
        public ?int $user_id;
        public string $purchase_date;
        public float $subtotal;
        public float $tax;
        public float $total_amount;
        public string $payment_method;
        public string $payment_status;
        public bool $is_online;
        public string $status;
        public ?string $notes;
        public string $created_at;
        public ?string $updated_at;
        public ?array $items;

        public function __construct(array $data = [])
        {
                $this->id = $data['id'] ?? null;
                $this->invoice_number = $data['invoice_number'] ?? '';
                $this->customer_id = $data['customer_id'] ?? 0;
                $this->user_id = $data['user_id'] ?? null;
                $this->purchase_date = $data['purchase_date'] ?? date('Y-m-d H:i:s');
                $this->subtotal = (float) ($data['subtotal'] ?? 0);
                $this->tax = (float) ($data['tax'] ?? 0);
                $this->total_amount = (float) ($data['total_amount'] ?? 0);
                $this->payment_method = $data['payment_method'] ?? '';
                $this->payment_status = $data['payment_status'] ?? 'pending';  // ← Puede ser 'paid'
                $this->is_online = (bool) ($data['is_online'] ?? false);
                $this->status = $data['status'] ?? 'active';
                $this->notes = $data['notes'] ?? null;
                $this->created_at = $data['created_at'] ?? '';
                $this->updated_at = $data['updated_at'] ?? null;
                $this->items = $data['items'] ?? [];
        }
}
