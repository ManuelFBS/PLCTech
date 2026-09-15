<?php

namespace PLCTech\Application\UseCases\Category;

use PLCTech\Domain\Entities\Category;
use PLCTech\Domain\Repositories\CategoryRepositoryInterface;
use PLCTech\Application\DTOs\CategoryDTO;

class UpdateCategoryUseCase
{
        private CategoryRepositoryInterface $categoryRepository;

        public function __construct(CategoryRepositoryInterface $categoryRepository)
        {
                $this->categoryRepository = $categoryRepository;
        }

        public function execute(int $id, CategoryDTO $categoryDTO): array
        {
                $existingCategory = $this->categoryRepository->find($id);

                if (!$existingCategory) {
                        throw new \Exception('Categoría no encontrada');
                }

                // > Validar nombre único (excluyendo la actual)...
                $categoryByName = $this->categoryRepository->findByName($categoryDTO->name);
                if ($categoryByName && $categoryByName->getId() !== $id) {
                        throw new \Exception('Ya existe una categoría con ese nombre');
                }

                // > Validar nombre no vacío...
                if (empty(trim($categoryDTO->name))) {
                        throw new \Exception('El nombre de la categoría es obligatorio');
                }

                // > Crear categoría actualizada...
                $updatedCategory = new Category(
                        $id,
                        trim($categoryDTO->name),
                        $categoryDTO->description,
                        $existingCategory->getCreatedAt(),
                );

                $this->categoryRepository->update($updatedCategory);

                return [
                        'success' => true,
                        'message' => 'Categoría actualizada exitosamente',
                ];
        }
}
