<?php

namespace PLCTech\Application\UseCases\Purchase;

use PLCTech\Application\DTOs\PurchaseDTO;
use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\Purchase;
use PLCTech\Domain\Entities\PurchaseItem;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;
use PLCTech\Domain\Repositories\ProductRepositoryInterface;
use PLCTech\Domain\Repositories\PurchaseItemRepositoryInterface;
use PLCTech\Domain\Repositories\PurchaseRepositoryInterface;

class CreatePurchaseUseCase
{
        private PurchaseRepositoryInterface $purchaseRepository;
        private PurchaseItemRepositoryInterface $purchaseItemRepository;
        private ProductRepositoryInterface $productRepository;
        private CustomerRepositoryInterface $customerRepository;
        private \PDO $db;

        public function __construct(
                PurchaseRepositoryInterface $purchaseRepository,
                PurchaseItemRepositoryInterface $purchaseItemRepository,
                ProductRepositoryInterface $productRepository,
                CustomerRepositoryInterface $customerRepository
        ) {
                $this->purchaseRepository = $purchaseRepository;
                $this->purchaseItemRepository = $purchaseItemRepository;
                $this->productRepository = $productRepository;
                $this->customerRepository = $customerRepository;
                $this->db = Database::getConnection();  // ← Obtener conexión PDO
        }

        public function execute(PurchaseDTO $purchaseDTO): array
        {
                // > 1. Validar que el cliente existe...
                $customer = $this->customerRepository->find($purchaseDTO->customer_id);
                if (!$customer) {
                        throw new \Exception('Cliente no encontrado');
                }

                // > 2. Validar que hay items en la venta...
                if (empty($purchaseDTO->items)) {
                        throw new \Exception('La venta debe tener al menos un producto');
                }

                // > 3. Validar stock y calcular totales...
                $subtotal = 0;
                $items = [];

                foreach ($purchaseDTO->items as $itemData) {
                        $product = $this->productRepository->find($itemData['product_id']);
                        if (!$product) {
                                throw new \Exception("Producto ID {$itemData['product_id']} no encontrado");
                        }

                        // ? Verificar stock disponible...
                        if ($product->getStock() < $itemData['quantity']) {
                                throw new \Exception(
                                        "Stock insuficiente para '{$product->getName()}'. "
                                        . "Disponible: {$product->getStock()}, Solicitado: {$itemData['quantity']}"
                                );
                        }

                        // ? Calcular subtotal...
                        $unitPrice = $product->getPrice();
                        $itemSubtotal = $unitPrice * $itemData['quantity'];
                        $subtotal += $itemSubtotal;

                        $items[] = [
                                'product' => $product,
                                'quantity' => $itemData['quantity'],
                                'unit_price' => $unitPrice,
                                'subtotal' => $itemSubtotal
                        ];
                }

                // > 4. Calcular impuestos y total...
                $tax = $subtotal * 0.16;
                $total = $subtotal + $tax;

                // > 5. Generar número de factura...
                $invoiceNumber = $this->generateInvoiceNumber();

                // > 6. Crear la entidad Purchase...
                $purchase = new Purchase(
                        null,
                        $invoiceNumber,
                        $purchaseDTO->customer_id,
                        $purchaseDTO->user_id ?? null,
                        date('Y-m-d H:i:s'),
                        $subtotal,
                        $tax,
                        $total,
                        $purchaseDTO->payment_method ?? 'cash',
                        $purchaseDTO->payment_status ?? 'pending',
                        $purchaseDTO->is_online ?? false,
                        'active',
                        $purchaseDTO->notes ?? null
                );

                // > ============================================================
                // > INICIAR TRANSACCIÓN (USANDO PDO DIRECTAMENTE)
                // > ============================================================
                $this->db->beginTransaction();

                try {
                        // > 7. Guardar la compra...
                        $purchaseId = $this->purchaseRepository->save($purchase);

                        // > 8. Guardar los items (SIN subtotal)...
                        foreach ($items as $item) {
                                $purchaseItem = new PurchaseItem(
                                        null,
                                        $purchaseId,
                                        $item['product']->getId(),
                                        $item['quantity'],
                                        $item['unit_price'],
                                        $item['subtotal']
                                );
                                $this->purchaseItemRepository->save($purchaseItem);

                                // > 9. Actualizar stock del producto...
                                $item['product']->setStock(
                                        $item['product']->getStock() - $item['quantity']
                                );
                                $this->productRepository->update($item['product']);
                        }

                        // > ============================================================
                        // > CONFIRMAR TRANSACCIÓN
                        // > ============================================================
                        $this->db->commit();

                        return [
                                'success' => true,
                                'message' => 'Venta creada exitosamente',
                                'purchase_id' => $purchaseId,
                                'invoice_number' => $invoiceNumber,
                                'total' => $total
                        ];
                } catch (\Exception $e) {
                        // > ============================================================
                        // > REVERTIR TRANSACCIÓN EN CASO DE ERROR
                        // > ============================================================
                        $this->db->rollBack();
                        throw $e;
                }
        }

        private function generateInvoiceNumber(): string
        {
                $prefix = 'PLC';
                $date = date('Y-m-d');
                $lastInvoice = $this->purchaseRepository->findAll();
                $lastNumber = 0;

                foreach ($lastInvoice as $invoice) {
                        $num = (int) substr($invoice->getInvoiceNumber(), -5);
                        if ($num > $lastNumber) {
                                $lastNumber = $num;
                        }
                }

                $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                return "{$prefix}-{$date}-{$nextNumber}";
        }
}
