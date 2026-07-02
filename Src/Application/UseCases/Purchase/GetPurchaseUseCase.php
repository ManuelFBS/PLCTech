<?php

namespace PLCTech\Application\UseCases\Purchase;

use PLCTech\Application\DTOs\PurchaseDTO;
use PLCTech\Domain\Repositories\PurchaseItemRepositoryInterface;
use PLCTech\Domain\Repositories\PurchaseRepositoryInterface;

class GetPurchaseUseCase
{
        private PurchaseRepositoryInterface $purchaseRepository;
        private PurchaseItemRepositoryInterface $purchaseItemRepository;

        public function __construct(
                PurchaseRepositoryInterface $purchaseRepository,
                PurchaseItemRepositoryInterface $purchaseItemRepository
        ) {
                $this->purchaseRepository = $purchaseRepository;
                $this->purchaseItemRepository = $purchaseItemRepository;
        }

        public function execute(int $id): ?PurchaseDTO
        {
                $purchase = $this->purchaseRepository->find($id);

                if (!$purchase) {
                        return null;
                }

                // * Obtener los items de la compra...
                $items = $this->purchaseItemRepository->findByPurchase($id);
                $itemsData = [];

                foreach ($items as $item) {
                        $itemsData[] = [
                                'id' => $item->getId(),
                                'product_id' => $item->getProductId(),
                                'quantity' => $item->getQuantity(),
                                'unit_price' => $item->getUnitPrice(),
                                'subtotal' => $item->getSubtotal()
                        ];
                }

                return new PurchaseDTO([
                        'id' => $purchase->getId(),
                        'invoice_number' => $purchase->getInvoiceNumber(),
                        'customer_id' => $purchase->getCustomerId(),
                        'user_id' => $purchase->getUserId(),
                        'purchase_date' => $purchase->getPurchaseDate(),
                        'subtotal' => $purchase->getSubtotal(),
                        'tax' => $purchase->getTax(),
                        'total_amount' => $purchase->getTotalAmount(),
                        'payment_method' => $purchase->getPaymentMethod(),
                        'payment_status' => $purchase->getPaymentStatus(),
                        'is_online' => $purchase->isOnline(),
                        'status' => $purchase->getStatus(),
                        'notes' => $purchase->getNotes(),
                        'created_at' => $purchase->getCreatedAt(),
                        'updated_at' => $purchase->getUpdatedAt(),
                        'items' => $itemsData
                ]);
        }
}
