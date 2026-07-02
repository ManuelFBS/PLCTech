<?php

namespace PLCTech\Application\UseCases\Purchase;

use PLCTech\Domain\Repositories\ProductRepositoryInterface;
use PLCTech\Domain\Repositories\PurchaseItemRepositoryInterface;
use PLCTech\Domain\Repositories\PurchaseRepositoryInterface;

class CancelPurchaseUseCase
{
        private PurchaseRepositoryInterface $purchaseRepository;
        private PurchaseItemRepositoryInterface $purchaseItemRepository;
        private ProductRepositoryInterface $productRepository;

        public function __construct(
                PurchaseRepositoryInterface $purchaseRepository,
                PurchaseItemRepositoryInterface $purchaseItemRepository,
                ProductRepositoryInterface $productRepository
        ) {
                $this->purchaseRepository = $purchaseRepository;
                $this->purchaseItemRepository = $purchaseItemRepository;
                $this->productRepository = $productRepository;
        }

        public function execute(int $id, string $reason = ''): array
        {
                $purchase = $this->purchaseRepository->find($id);

                if (!$purchase) {
                        throw new \Exception('Venta no encontrada');
                }

                if ($purchase->isCancelled()) {
                        throw new \Exception('La venta ya está anulada');
                }

                // * 1. Obtener los items de la compra...
                $items = $this->purchaseItemRepository->findByPurchase($id);

                // * 2. Restaurar el stock de los productos...
                foreach ($items as $item) {
                        $product = $this->productRepository->find($item->getProductId());
                        if ($product) {
                                $product->setStock($product->getStock() + $item->getQuantity());
                                $this->productRepository->update($product);
                        }
                }

                // * 3. Actualizar el estado de la compra...
                $purchase->setStatus('cancelled');
                $purchase->setPaymentStatus('cancelled');

                // * 4. Agregar nota de cancelación...
                $notes = $purchase->getNotes() ?? '';
                $cancelNote = 'ANULADA: ' . date('Y-m-d H:i:s');
                if (!empty($reason)) {
                        $cancelNote .= ' - Motivo: ' . $reason;
                }
                $purchase->setNotes(trim($notes . "\n" . $cancelNote));

                // * 5. Guardar cambios...
                $this->purchaseRepository->update($purchase);

                return [
                        'success' => true,
                        'message' => 'Venta anulada exitosamente',
                        'purchase_id' => $id,
                        'invoice_number' => $purchase->getInvoiceNumber()
                ];
        }
}
