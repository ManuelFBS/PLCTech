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
                // > Debug...
                error_log('=== CreateProductUseCase ===');
                error_log('category_id en DTO: ' . ($productDTO->category_id ?? 'null'));

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

                // > Crear entidad con los tipos correctos...
                $product = new Product(
                        null,  // id
                        $productDTO->name,  // name
                        $productDTO->description,  // description
                        $productDTO->category_id,  // category_id (int o null)
                        $productDTO->image_prod,  // image_prod
                        $productDTO->price,  // price
                        $productDTO->stock,  // stock
                        $productDTO->is_active  // is_active
                );

                error_log('category_id en Product: ' . ($product->getCategoryId() ?? 'null'));

                $productId = $this->productRepository->save($product);

                return [
                        'success' => true,
                        'message' => 'Producto creado exitosamente',
                        'product_id' => $productId
                ];
        }
}
