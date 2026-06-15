<?php

namespace PLCTech\Application\UseCases\Product;

use PLCTech\Application\DTOs\ProductDTO;
use PLCTech\Domain\Entities\Product;
use PLCTech\Domain\Repositories\ProductRepositoryInterface;

class CreateProductUseCase
{
        private ProductRepositoryInterface $productRepository;

        public function __construct(ProductRepositoryInterface $productRepository)
        {
                $this->productRepository = $productRepository;
        }

        public function execute(ProductDTO $productDTO): array
        {
                // > Validar nombre único...
                if ($this->productRepository->findByName($productDTO->name)) {
                        throw new \Exception('Ya existe un producto con ese nombre');
                }

                // > Validar precio positivo...
                if ($productDTO->price < 0) {
                        throw new \Exception('El precio no puede ser negativo');
                }

                // > Validar stock no negativo...
                if ($productDTO->stock < 0) {
                        throw new \Exception('El stock no puede ser negativo');
                }

                // > Crear entidad...
                $product = new Product(
                        null,
                        $productDTO->name,
                        $productDTO->description,
                        $productDTO->image_prod,
                        $productDTO->price,
                        $productDTO->stock,
                        $productDTO->is_active
                );

                $productId = $this->productRepository->save($product);

                return [
                        'success' => true,
                        'message' => 'Producto creado exitosamente',
                        'product_id' => $productId
                ];
        }
}
