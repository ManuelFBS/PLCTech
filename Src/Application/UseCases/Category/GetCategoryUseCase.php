<?php

namespace PLCTech\Application\UseCases\Category;

use PLCTech\Domain\Repositories\CategoryRepositoryInterface;
use PLCTech\Application\DTOs\CategoryDTO;

class GetCategoryUseCase
{
        private CategoryRepositoryInterface $categoryRepository;

        public function __construct(CategoryRepositoryInterface $categoryRepository)
        {
                $this->categoryRepository = $categoryRepository;
        }

        public function execute(int $id): ?CategoryDTO
        {
                $category = $this->categoryRepository->find($id);

                if (!$category) {
                        return null;
                }

                return new CategoryDTO([
                        'id' => $category->getId(),
                        'name' => $category->getName(),
                        'description' => $category->getDescription(),
                        'created_at' => $category->getCreatedAt(),
                ]);
        }
}
