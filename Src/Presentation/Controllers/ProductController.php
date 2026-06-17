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
                        // > Procesar la imagen si se subió...
                        $imageName = null;
                        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $imageName = $this->uploadImage($_FILES['image']);
                        }

                        $productDTO = new ProductDTO([
                                'name' => $_POST['name'] ?? '',
                                'description' => $_POST['description'] ?? null,
                                'image_prod' => $imageName,
                                'price' => (float) ($_POST['price'] ?? 0),
                                'stock' => (int) ($_POST['stock'] ?? 0),
                                'is_active' => isset($_POST['is_active'])
                                        ? (bool) $_POST['is_active']
                                        : true
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
                        // > Obtener el producto actual para verificar si tiene imagen...
                        $currentProduct = $this->getProductUseCase->execute((int) $id);
                        $currentImage = $currentProduct ? $currentProduct->image_prod : null;

                        // > Procesar nueva imagen si se subió...
                        $imageName = $currentImage;  // Mantener la imagen actual por defecto
                        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                // Si hay una imagen nueva, eliminar la anterior
                                if ($currentImage) {
                                        $this->deleteImage($currentImage);
                                }
                                $imageName = $this->uploadImage($_FILES['image']);
                        }

                        $productDTO = new ProductDTO([
                                'name' => $_POST['name'] ?? '',
                                'description' => $_POST['description'] ?? null,
                                'image_prod' => $imageName,
                                'price' => (float) ($_POST['price'] ?? 0),
                                'stock' => (int) ($_POST['stock'] ?? 0),
                                'is_active' => isset($_POST['is_active']) ? (bool) $_POST['is_active'] : true
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
                        // > Obtener el producto para eliminar su imagen...
                        $product = $this->getProductUseCase->execute((int) $id);
                        if ($product && $product->image_prod) {
                                $this->deleteImage($product->image_prod);
                        }

                        $result = $this->deleteProductUseCase->execute((int) $id);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/products');
                exit;
        }

        /**
         * * Sube una imagen al servidor
         *
         * @param array $file Archivo subido ($_FILES['image'])
         * @return string|null Nombre del archivo guardado o null si falla
         * // @throws Exception Si hay error en la subida
         */
        private function uploadImage(array $file): ?string
        {
                // > Validar que sea una imagen..
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, $allowedTypes)) {
                        throw new \Exception('Tipo de archivo no permitido. Use: JPG, PNG, GIF o WEBP');
                }

                // > Validar tamaño máximo (2MB)...
                $maxSize = 2 * 1024 * 1024;  // 2MB
                if ($file['size'] > $maxSize) {
                        throw new \Exception('El archivo excede el tamaño máximo permitido (2MB)');
                }

                // > Crear directorio si no existe...
                $uploadDir = __DIR__ . '/../../../uploads/products/';
                if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                }

                // > Generar nombre único...
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = uniqid() . '_' . time() . '.' . $extension;
                $destination = $uploadDir . $newFileName;

                // > Mover el archivo...
                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                        throw new \Exception('Error al guardar la imagen');
                }

                // > Registrar en el log que se subió la imagen (opcional)...
                error_log('Imagen subida: ' . $newFileName);

                return $newFileName;
        }

        // * Elimina una imagen del servidor...
        private function deleteImage(string $imageName): void
        {
                $uploadDir = __DIR__ . '/../../../uploads/products/';
                $filePath = $uploadDir . $imageName;

                if (file_exists($filePath)) {
                        unlink($filePath);
                        error_log('Imagen eliminada: ' . $imageName);
                }
        }
}
