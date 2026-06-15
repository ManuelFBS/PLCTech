<?php

namespace PLCTech\Application\UseCases\Product;

use PLCTech\Application\DTOs\ProductDTO;
use PLCTech\Domain\Repositories\ProductRepositoryInterface;

class ListProductsUseCase
{
        private ProductRepositoryInterface $productRepository;

        public function __construct(ProductRepositoryInterface $productRepository)
        {
                $this->productRepository = $productRepository;
        }

        public function execute(): array
        {
                $products = $this->productRepository->findAll();
                $productsDTO = [];

                foreach ($products as $product) {
                        $productsDTO[] = new ProductDTO([
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

                return $productsDTO;
        }
}
