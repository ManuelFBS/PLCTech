<?php

namespace PLCTech\Application\UseCases\Product;

use PLCTech\Domain\Repositories\ProductRepositoryInterface;
use PLCTech\Domain\Repositories\PurchaseItemRepositoryInterface;

class DeleteProductUseCase
{
        private ProductRepositoryInterface $productRepository;
        private PurchaseItemRepositoryInterface $purchaseItemRepository;

        public function __construct(
                ProductRepositoryInterface $productRepository,
                PurchaseItemRepositoryInterface $purchaseItemRepository
        ) {
                $this->productRepository = $productRepository;
                $this->purchaseItemRepository = $purchaseItemRepository;
        }

        public function execute(int $id): array
        {
                $product = $this->productRepository->find($id);

                if (!$product) {
                        throw new \Exception('Producto no encontrado');
                }

                // Verificar si tiene ventas asociadas
                $hasSales = $this->purchaseItemRepository->hasPurchaseItems($id);
                if ($hasSales) {
                        throw new \Exception('No se puede eliminar el producto porque tiene ventas asociadas');
                }

                $this->productRepository->delete($id);

                return [
                        'success' => true,
                        'message' => 'Producto eliminado exitosamente'
                ];
        }
}
