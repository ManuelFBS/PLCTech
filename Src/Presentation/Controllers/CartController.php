<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Domain\Entities\Cart;
use PLCTech\Helpers\PathHelper;
use PLCTech\Infrastructure\Database\Repositories\MySQLCartRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLProductRepository;

class CartController
{
        private MySQLCartRepository $cartRepository;
        private MySQLProductRepository $productRepository;

        public function __construct()
        {
                $this->cartRepository = new MySQLCartRepository();
                $this->productRepository = new MySQLProductRepository();
        }

        // * ============================================================
        // * VER CARRITO
        // * ============================================================
        public function index(): void
        {
                try {
                        $customerId = $_SESSION['customer_id'] ?? null;

                        if (!$customerId) {
                                $_SESSION['error_message'] = 'Debe iniciar sesión para ver su carrito';
                                header('Location: ' . PathHelper::getBaseUrl() . '/login');
                                exit;
                        }

                        // > Obtener items del carrito con datos del producto...
                        $cartItems = $this->cartRepository->findByCustomer((int) $customerId);

                        // > Enriquecer con datos del producto...
                        $cartWithProducts = [];
                        $subtotal = 0;

                        foreach ($cartItems as $item) {
                                $product = $this->productRepository->find($item->getProductId());
                                if ($product) {
                                        $itemSubtotal = $product->getPrice() * $item->getQuantity();
                                        $subtotal += $itemSubtotal;

                                        $cartWithProducts[] = [
                                                'id' => $item->getId(),
                                                'product_id' => $item->getProductId(),
                                                'name' => $product->getName(),
                                                'image_prod' => $product->getImageProd(),
                                                'price' => $product->getPrice(),
                                                'quantity' => $item->getQuantity(),
                                                'subtotal' => $itemSubtotal,
                                                'stock' => $product->getStock()
                                        ];
                                }
                        }

                        $cart = $cartWithProducts;
                        $tax = $subtotal * 0.16;
                        $total = $subtotal + $tax;

                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/cart/index.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl());
                }
        }

        // * ============================================================
        // * AGREGAR AL CARRITO (AJAX o POST)
        // * ============================================================
        public function add(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/products/catalog');
                        exit;
                }

                try {
                        $customerId = $_SESSION['customer_id'] ?? null;

                        if (!$customerId) {
                                throw new \Exception('Debe iniciar sesión para agregar productos al carrito');
                        }

                        $productId = (int) ($_POST['product_id'] ?? 0);
                        $quantity = (int) ($_POST['quantity'] ?? 1);

                        if ($productId <= 0) {
                                throw new \Exception('Producto inválido');
                        }

                        if ($quantity <= 0) {
                                throw new \Exception('La cantidad debe ser mayor a 0');
                        }

                        // > Verificar que el producto existe y tiene stock...
                        $product = $this->productRepository->find($productId);
                        if (!$product) {
                                throw new \Exception('Producto no encontrado');
                        }

                        if ($product->getStock() < $quantity) {
                                throw new \Exception('Stock insuficiente. Disponible: ' . $product->getStock());
                        }

                        // > Agregar al carrito...
                        $cart = new Cart(
                                null,
                                (int) $customerId,
                                $productId,
                                $quantity
                        );

                        $this->cartRepository->save($cart);

                        $_SESSION['success_message'] = 'Producto agregado al carrito';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                // > Redirigir de vuelta al catálogo...
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? PathHelper::getBaseUrl() . '/products/catalog'));
                exit;
        }

        // * ============================================================
        // * ACTUALIZAR CANTIDAD (AJAX)
        // * ============================================================
        public function update(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/cart');
                        exit;
                }

                try {
                        $customerId = $_SESSION['customer_id'] ?? null;

                        if (!$customerId) {
                                throw new \Exception('Debe iniciar sesión');
                        }

                        $cartId = (int) ($_POST['cart_id'] ?? 0);
                        $quantity = (int) ($_POST['quantity'] ?? 0);

                        if ($cartId <= 0) {
                                throw new \Exception('Item de carrito inválido');
                        }

                        if ($quantity <= 0) {
                                // > Si cantidad es 0 o negativa, eliminar el item...
                                $this->cartRepository->delete($cartId);
                                $_SESSION['success_message'] = 'Producto eliminado del carrito';
                        } else {
                                // > Actualizar cantidad...
                                $cartItem = $this->cartRepository->find($cartId);
                                if (!$cartItem) {
                                        throw new \Exception('Item no encontrado');
                                }

                                // > Verificar stock disponible...
                                $product = $this->productRepository->find($cartItem->getProductId());
                                if ($product && $product->getStock() < $quantity) {
                                        throw new \Exception('Stock insuficiente. Disponible: ' . $product->getStock());
                                }

                                $cartItem->setQuantity($quantity);
                                $this->cartRepository->update($cartItem);
                                $_SESSION['success_message'] = 'Carrito actualizado';
                        }
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/cart');
                exit;
        }

        // * ============================================================
        // * ELIMINAR DEL CARRITO
        // * ============================================================
        public function remove(): void
        {
                $cartId = $_GET['id'] ?? 0;

                try {
                        if ($cartId <= 0) {
                                throw new \Exception('Item de carrito inválido');
                        }

                        $this->cartRepository->delete((int) $cartId);
                        $_SESSION['success_message'] = 'Producto eliminado del carrito';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/cart');
                exit;
        }

        // * ============================================================
        // * VACIAR CARRITO
        // * ============================================================
        public function clear(): void
        {
                try {
                        $customerId = $_SESSION['customer_id'] ?? null;

                        if (!$customerId) {
                                throw new \Exception('Debe iniciar sesión');
                        }

                        $this->cartRepository->clearByCustomer((int) $customerId);
                        $_SESSION['success_message'] = 'Carrito vaciado correctamente';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/cart');
                exit;
        }
}
