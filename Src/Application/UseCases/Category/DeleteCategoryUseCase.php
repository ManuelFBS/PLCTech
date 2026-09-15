<?php

namespace PLCTech\Application\UseCases\Category;

use PLCTech\Domain\Repositories\CategoryRepositoryInterface;

class DeleteCategoryUseCase
{
        private CategoryRepositoryInterface $categoryRepository;

        public function __construct(CategoryRepositoryInterface $categoryRepository)
        {
                $this->categoryRepository = $categoryRepository;
        }

        public function execute(int $id): array
        {
                $category = $this->categoryRepository->find($id);

                if (!$category) {
                        throw new \Exception('Categoría no encontrada');
                }

                // ! Verificar si tiene productos asociados...
                // ! Nota: Esto requiere un ProductRepository...
                // ! Por ahora, solo eliminamos la categoría...

                $this->categoryRepository->delete($id);

                return [
                        'success' => true,
                        'message' => 'Categoría eliminada exitosamente',
                ];
        }
}
