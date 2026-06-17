<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\ProductDTO;
use PLCTech\Application\UseCases\Product\CreateProductUseCase;
use PLCTech\Application\UseCases\Product\DeleteProductUseCase;
use PLCTech\Application\UseCases\Product\GetProductUseCase;
use PLCTech\Application\UseCases\Product\ListProductsUseCase;
use PLCTech\Application\UseCases\Product\UpdateProductUseCase;
use PLCTech\Helpers\PathHelper;
use PLCTech\Infrastructure\Database\Repositories\MySQLProductRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLPurchaseItemRepository;

class ProductController
{
        private ListProductsUseCase $listProductsUseCase;
        private CreateProductUseCase $createProductUseCase;
        private GetProductUseCase $getProductUseCase;
        private UpdateProductUseCase $updateProductUseCase;
        private DeleteProductUseCase $deleteProductUseCase;

        public function __construct()
        {
                $productRepository = new MySQLProductRepository();
                $purchaseItemRepository = new MySQLPurchaseItemRepository();

                $this->listProductsUseCase = new ListProductsUseCase($productRepository);
                $this->createProductUseCase = new CreateProductUseCase($productRepository);
                $this->getProductUseCase = new GetProductUseCase($productRepository);
                $this->updateProductUseCase = new UpdateProductUseCase($productRepository);
                $this->deleteProductUseCase = new DeleteProductUseCase($productRepository, $purchaseItemRepository);
        }

        public function index(): void
        {
                try {
                        $products = $this->listProductsUseCase->execute();
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/products/index.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl());
                }
        }

        public function show(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $productDTO = $this->getProductUseCase->execute((int) $id);

                        if (!$productDTO) {
                                throw new \Exception('Producto no encontrado');
                        }

                        $product = $productDTO;
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/products/show.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/products');
                        exit;
                }
        }

        public function create(): void
        {
                try {
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/products/create.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/products');
                        exit;
                }
        }

        public function store(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/products');
                        exit;
                }

                try {
                        $productDTO = new ProductDTO([
                                'name' => $_POST['name'] ?? '',
                                'description' => $_POST['description'] ?? null,
                                'image_prod' => $_POST['image_prod'] ?? null,
                                'price' => (float) ($_POST['price'] ?? 0),
                                'stock' => (int) ($_POST['stock'] ?? 0),
                                'is_active' => isset($_POST['is_active']) ? true : false
                        ]);

                        $result = $this->createProductUseCase->execute($productDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/products');
                exit;
        }

        public function edit(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $productDTO = $this->getProductUseCase->execute((int) $id);

                        if (!$productDTO) {
                                throw new \Exception('Producto no encontrado');
                        }

                        $product = $productDTO;
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/products/edit.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/products');
                        exit;
                }
        }

        public function update(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/products');
                        exit;
                }

                $id = $_POST['id'] ?? 0;

                try {
                        $productDTO = new ProductDTO([
                                'name' => $_POST['name'] ?? '',
                                'description' => $_POST['description'] ?? null,
                                'image_prod' => $_POST['image_prod'] ?? null,
                                'price' => (float) ($_POST['price'] ?? 0),
                                'stock' => (int) ($_POST['stock'] ?? 0),
                                'is_active' => isset($_POST['is_active']) ? true : false
                        ]);

                        $result = $this->updateProductUseCase->execute((int) $id, $productDTO);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/products');
                exit;
        }

        public function delete(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $result = $this->deleteProductUseCase->execute((int) $id);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/products');
                exit;
        }
}
