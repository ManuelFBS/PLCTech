<?php

namespace PLCTech\Domain\Entities;

class Purchase
{
        private ?int $id;
        private string $invoice_number;
        private int $customer_id;
        private ?int $user_id;
        private string $purchase_date;
        private float $subtotal;
        private float $tax;
        private float $total_amount;
        private string $payment_method;
        private string $payment_status;
        private bool $is_online;
        private string $status;
        private ?string $notes;
        private string $created_at;
        private ?string $updated_at;

        public function __construct(
                ?int $id,
                string $invoice_number,
                int $customer_id,
                ?int $user_id,
                string $purchase_date,
                float $subtotal,
                float $tax,
                float $total_amount,
                string $payment_method,
                string $payment_status = 'pending',
                bool $is_online = false,
                string $status = 'active',
                ?string $notes = null,
                string $created_at = '',
                ?string $updated_at = null
        ) {
                $this->id = $id;
                $this->invoice_number = $invoice_number;
                $this->customer_id = $customer_id;
                $this->user_id = $user_id;
                $this->purchase_date = $purchase_date;
                $this->subtotal = $subtotal;
                $this->tax = $tax;
                $this->total_amount = $total_amount;
                $this->payment_method = $payment_method;
                $this->payment_status = $payment_status;
                $this->is_online = $is_online;
                $this->status = $status;
                $this->notes = $notes;
                $this->created_at = $created_at;
                $this->updated_at = $updated_at;
        }

        // * Getters...
        public function getId(): ?int
        {
                return $this->id;
        }

        public function getInvoiceNumber(): string
        {
                return $this->invoice_number;
        }

        public function getCustomerId(): int
        {
                return $this->customer_id;
        }

        public function getUserId(): ?int
        {
                return $this->user_id;
        }

        public function getPurchaseDate(): string
        {
                return $this->purchase_date;
        }

        public function getSubtotal(): float
        {
                return $this->subtotal;
        }

        public function getTax(): float
        {
                return $this->tax;
        }

        public function getTotalAmount(): float
        {
                return $this->total_amount;
        }

        public function getPaymentMethod(): string
        {
                return $this->payment_method;
        }

        public function getPaymentStatus(): string
        {
                return $this->payment_status;
        }

        public function isOnline(): bool
        {
                return $this->is_online;
        }

        public function getStatus(): string
        {
                return $this->status;
        }

        public function getNotes(): ?string
        {
                return $this->notes;
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
        public function setPaymentStatus(string $payment_status): void
        {
                $this->payment_status = $payment_status;
        }

        public function setStatus(string $status): void
        {
                $this->status = $status;
        }

        public function setNotes(?string $notes): void
        {
                $this->notes = $notes;
        }

        // * Métodos útiles...
        public function isPaid(): bool
        {
                return $this->payment_status === 'paid';
        }

        public function isPending(): bool
        {
                return $this->payment_status === 'pending';
        }

        public function isCancelled(): bool
        {
                return $this->status === 'cancelled' || $this->payment_status === 'cancelled';
        }

        public function isActive(): bool
        {
                return $this->status === 'active';
        }
}
