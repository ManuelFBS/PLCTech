<?php

namespace PLCTech\Application\UseCases\Purchase;

use PLCTech\Application\DTOs\PurchaseDTO;
use PLCTech\Domain\Repositories\PurchaseRepositoryInterface;

class ListPurchasesUseCase
{
        private PurchaseRepositoryInterface $purchaseRepository;

        public function __construct(PurchaseRepositoryInterface $purchaseRepository)
        {
                $this->purchaseRepository = $purchaseRepository;
        }

        public function execute(): array
        {
                $purchases = $this->purchaseRepository->findAll();
                $purchasesDTO = [];

                foreach ($purchases as $purchase) {
                        $purchasesDTO[] = new PurchaseDTO([
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
                                'updated_at' => $purchase->getUpdatedAt()
                        ]);
                }

                return $purchasesDTO;
        }
}
