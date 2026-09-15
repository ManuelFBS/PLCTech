<?php

namespace PLCTech\Application\UseCases\Category;

use PLCTech\Domain\Entities\Category;
use PLCTech\Domain\Repositories\CategoryRepositoryInterface;
use PLCTech\Application\DTOs\CategoryDTO;

class CreateCategoryUseCase
{
        private CategoryRepositoryInterface $categoryRepository;

        public function __construct(CategoryRepositoryInterface $categoryRepository)
        {
                $this->categoryRepository = $categoryRepository;
        }

        public function execute(CategoryDTO $categoryDTO): array
        {
                // > Validar nombre único...
                if ($this->categoryRepository->findByName($categoryDTO->name)) {
                        throw new \Exception('Ya existe una categoría con ese nombre');
                }

                // > Validar nombre no vacío...
                if (empty(trim($categoryDTO->name))) {
                        throw new \Exception('El nombre de la categoría es obligatorio');
                }

                // > Crear entidad...
                $category = new Category(null, trim($categoryDTO->name), $categoryDTO->description);

                $categoryId = $this->categoryRepository->save($category);

                return [
                        'success' => true,
                        'message' => 'Categoría creada exitosamente',
                        'category_id' => $categoryId,
                ];
        }
}
