<?php

namespace PLCTech\Application\UseCases\Product;

use PLCTech\Application\DTOs\ProductDTO;
use PLCTech\Domain\Entities\Product;
use PLCTech\Domain\Repositories\ProductRepositoryInterface;

class UpdateProductUseCase
{
        private ProductRepositoryInterface $productRepository;

        public function __construct(ProductRepositoryInterface $productRepository)
        {
                $this->productRepository = $productRepository;
        }

        public function execute(int $id, ProductDTO $productDTO): array
        {
                $existingProduct = $this->productRepository->find($id);

                if (!$existingProduct) {
                        throw new \Exception('Producto no encontrado');
                }

                // Validar nombre único (excluyendo el actual)
                $productByName = $this->productRepository->findByName($productDTO->name);
                if ($productByName && $productByName->getId() !== $id) {
                        throw new \Exception('Ya existe un producto con ese nombre');
                }

                // Validar precio positivo
                if ($productDTO->price < 0) {
                        throw new \Exception('El precio no puede ser negativo');
                }

                // Validar stock no negativo
                if ($productDTO->stock < 0) {
                        throw new \Exception('El stock no puede ser negativo');
                }

                // Crear producto actualizado
                $updatedProduct = new Product(
                        $id,
                        $productDTO->name,
                        $productDTO->description,
                        $productDTO->category_id,  // ← int o null
                        $productDTO->image_prod,
                        $productDTO->price,
                        $productDTO->stock,
                        $productDTO->is_active,
                        $existingProduct->getCreatedAt()
                );

                $this->productRepository->update($updatedProduct);

                return [
                        'success' => true,
                        'message' => 'Producto actualizado exitosamente'
                ];
        }
}
