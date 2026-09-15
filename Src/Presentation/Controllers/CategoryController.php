<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\CategoryDTO;
use PLCTech\Application\UseCases\Category\CreateCategoryUseCase;
use PLCTech\Application\UseCases\Category\DeleteCategoryUseCase;
use PLCTech\Application\UseCases\Category\GetCategoryUseCase;
use PLCTech\Application\UseCases\Category\ListCategoriesUseCase;
use PLCTech\Application\UseCases\Category\UpdateCategoryUseCase;
use PLCTech\Helpers\PathHelper;
use PLCTech\Infrastructure\Database\Repositories\MySQLCategoryRepository;

class CategoryController
{
        private CreateCategoryUseCase $createCategoryUseCase;
        private GetCategoryUseCase $getCategoryUseCase;
        private ListCategoriesUseCase $listCategoriesUseCase;
        private UpdateCategoryUseCase $updateCategoryUseCase;
        private DeleteCategoryUseCase $deleteCategoryUseCase;
        private MySQLCategoryRepository $categoryRepository;

        public function __construct()
        {
                $categoryRepository = new MySQLCategoryRepository();
                $this->categoryRepository = $categoryRepository;

                $this->listCategoriesUseCase = new ListCategoriesUseCase($categoryRepository);
                $this->createCategoryUseCase = new CreateCategoryUseCase($categoryRepository);
                $this->getCategoryUseCase = new GetCategoryUseCase($categoryRepository);
                $this->updateCategoryUseCase = new UpdateCategoryUseCase($categoryRepository);
                $this->deleteCategoryUseCase = new DeleteCategoryUseCase($categoryRepository);
        }

        // * ============================================================
        // * LISTAR CATEGORÍAS
        // * ============================================================
        public function index(): void
        {
                try {
                        $categories = $this->listCategoriesUseCase->execute();
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/categories/index.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/products');
                        exit();
                }
        }

        // * ============================================================
        // * MOSTRAR FORMULARIO DE CREACIÓN
        // * ============================================================
        public function create(): void
        {
                try {
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/categories/create.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/categories');
                        exit();
                }
        }

        // * ============================================================
        // * GUARDAR NUEVA CATEGORÍA
        // * ============================================================
        public function store(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/categories');
                        exit();
                }

                try {
                        $categoryDTO = new CategoryDTO([
                                'name' => $_POST['name'] ?? '',
                                'description' => $_POST['description'] ?? null,
                        ]);

                        $result = $this->createCategoryUseCase->execute($categoryDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/categories');
                exit();
        }

        // * ============================================================
        // * MOSTRAR FORMULARIO DE EDICIÓN
        // * ============================================================
        public function edit(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $categoryDTO = $this->getCategoryUseCase->execute((int) $id);

                        if (!$categoryDTO) {
                                throw new \Exception('Categoría no encontrada');
                        }

                        $category = $categoryDTO;
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/categories/edit.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/categories');
                        exit();
                }
        }

        // * ============================================================
        // * ACTUALIZAR CATEGORÍA
        // * ============================================================
        public function update(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/categories');
                        exit();
                }

                $id = $_POST['id'] ?? 0;

                try {
                        $categoryDTO = new CategoryDTO([
                                'name' => $_POST['name'] ?? '',
                                'description' => $_POST['description'] ?? null,
                        ]);

                        $result = $this->updateCategoryUseCase->execute((int) $id, $categoryDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/categories');
                exit();
        }

        // * ============================================================
        // * ELIMINAR CATEGORÍA
        // * ============================================================
        public function delete(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $result = $this->deleteCategoryUseCase->execute((int) $id);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/categories');
                exit();
        }
}
