<?php

namespace PLCTech\Application\UseCases\Category;

use PLCTech\Domain\Repositories\CategoryRepositoryInterface;
use PLCTech\Application\DTOs\CategoryDTO;

class ListCategoriesUseCase
{
        private CategoryRepositoryInterface $categoryRepository;

        public function __construct(CategoryRepositoryInterface $categoryRepository)
        {
                $this->categoryRepository = $categoryRepository;
        }

        public function execute(): array
        {
                $categories = $this->categoryRepository->findAll();
                $categoriesDTO = [];

                foreach ($categories as $category) {
                        $categoriesDTO[] = new CategoryDTO([
                                'id' => $category->getId(),
                                'name' => $category->getName(),
                                'description' => $category->getDescription(),
                                'created_at' => $category->getCreatedAt(),
                        ]);
                }

                return $categoriesDTO;
        }
}
