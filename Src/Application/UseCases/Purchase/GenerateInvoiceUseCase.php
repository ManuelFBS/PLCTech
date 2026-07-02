<?php

namespace PLCTech\Application\UseCases\Purchase;

use PLCTech\Domain\Repositories\CustomerRepositoryInterface;
use PLCTech\Domain\Repositories\ProductRepositoryInterface;
use PLCTech\Domain\Repositories\PurchaseItemRepositoryInterface;
use PLCTech\Domain\Repositories\PurchaseRepositoryInterface;

class GenerateInvoiceUseCase
{
        private PurchaseRepositoryInterface $purchaseRepository;
        private PurchaseItemRepositoryInterface $purchaseItemRepository;
        private CustomerRepositoryInterface $customerRepository;
        private ProductRepositoryInterface $productRepository;

        public function __construct(
                PurchaseRepositoryInterface $purchaseRepository,
                PurchaseItemRepositoryInterface $purchaseItemRepository,
                CustomerRepositoryInterface $customerRepository,
                ProductRepositoryInterface $productRepository
        ) {
                $this->purchaseRepository = $purchaseRepository;
                $this->purchaseItemRepository = $purchaseItemRepository;
                $this->customerRepository = $customerRepository;
                $this->productRepository = $productRepository;
        }

        public function execute(int $purchaseId): array
        {
                // * Obtener la compra...
                $purchase = $this->purchaseRepository->find($purchaseId);
                if (!$purchase) {
                        throw new \Exception('Venta no encontrada');
                }

                // * Obtener el cliente...
                $customer = $this->customerRepository->find($purchase->getCustomerId());
                if (!$customer) {
                        throw new \Exception('Cliente no encontrado');
                }

                // * Obtener los items...
                $items = $this->purchaseItemRepository->findByPurchase($purchaseId);
                $itemsData = [];

                foreach ($items as $item) {
                        $product = $this->productRepository->find($item->getProductId());
                        $itemsData[] = [
                                'product_name' => $product ? $product->getName() : 'Producto eliminado',
                                'quantity' => $item->getQuantity(),
                                'unit_price' => $item->getUnitPrice(),
                                'subtotal' => $item->getSubtotal()
                        ];
                }

                // * Datos de la empresa (configurables)...
                $company = [
                        'name' => 'PLC Tech Pulse',
                        'ruc' => 'J-12345678-9',
                        'address' => 'Av. Principal, Ciudad',
                        'phone' => '+58-412-1234567',
                        'email' => 'ventas@plctechpulse.com'
                ];

                return [
                        'purchase' => [
                                'id' => $purchase->getId(),
                                'invoice_number' => $purchase->getInvoiceNumber(),
                                'date' => $purchase->getPurchaseDate(),
                                'subtotal' => $purchase->getSubtotal(),
                                'tax' => $purchase->getTax(),
                                'total' => $purchase->getTotalAmount(),
                                'payment_method' => $purchase->getPaymentMethod(),
                                'payment_status' => $purchase->getPaymentStatus(),
                                'is_online' => $purchase->isOnline(),
                                'status' => $purchase->getStatus()
                        ],
                        'customer' => [
                                'name' => $customer->getFullName(),
                                'dni' => $customer->getDni(),
                                'email' => $customer->getEmail(),
                                'phone' => $customer->getPhoneNumber()
                        ],
                        'items' => $itemsData,
                        'company' => $company
                ];
        }
}
