<?php

namespace PLCTech\Application\UseCases\Product;

use PLCTech\Application\DTOs\ProductDTO;
use PLCTech\Domain\Repositories\ProductRepositoryInterface;

class GetProductUseCase
{
        private ProductRepositoryInterface $productRepository;

        public function __construct(ProductRepositoryInterface $productRepository)
        {
                $this->productRepository = $productRepository;
        }

        public function execute(int $id): ?ProductDTO
        {
                $product = $this->productRepository->find($id);

                if (!$product) {
                        return null;
                }

                return new ProductDTO([
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'description' => $product->getDescription(),
                        'image_prod' => $product->getImageProd(),
                        'price' => $product->getPrice(),
                        'stock' => $product->getStock(),
                        'is_active' => $product->isActive(),
                        'created_at' => $product->getCreatedAt(),
                        'updated_at' => $product->getUpdatedAt()
                ]);
        }
}
