<?php

namespace PLCTech\Presentation\Controllers;

use PLCTech\Application\DTOs\PurchaseDTO;
use PLCTech\Application\UseCases\Purchase\CancelPurchaseUseCase;
use PLCTech\Application\UseCases\Purchase\CreatePurchaseUseCase;
use PLCTech\Application\UseCases\Purchase\GenerateInvoiceUseCase;
use PLCTech\Application\UseCases\Purchase\GetPurchaseUseCase;
use PLCTech\Application\UseCases\Purchase\ListPurchasesUseCase;
use PLCTech\Helpers\PathHelper;
use PLCTech\Infrastructure\Database\Repositories\MySQLCustomerRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLProductRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLPurchaseItemRepository;
use PLCTech\Infrastructure\Database\Repositories\MySQLPurchaseRepository;

class PurchaseController
{
        private CreatePurchaseUseCase $createPurchaseUseCase;
        private ListPurchasesUseCase $listPurchasesUseCase;
        private GetPurchaseUseCase $getPurchaseUseCase;
        private CancelPurchaseUseCase $cancelPurchaseUseCase;
        private GenerateInvoiceUseCase $generateInvoiceUseCase;
        private MySQLProductRepository $productRepository;
        private MySQLCustomerRepository $customerRepository;

        public function __construct()
        {
                $purchaseRepository = new MySQLPurchaseRepository();
                $purchaseItemRepository = new MySQLPurchaseItemRepository();
                $productRepository = new MySQLProductRepository();
                $customerRepository = new MySQLCustomerRepository();

                $this->productRepository = $productRepository;
                $this->customerRepository = $customerRepository;

                $this->createPurchaseUseCase = new CreatePurchaseUseCase(
                        $purchaseRepository,
                        $purchaseItemRepository,
                        $productRepository,
                        $customerRepository
                );

                $this->listPurchasesUseCase =
                        new ListPurchasesUseCase($purchaseRepository);

                $this->getPurchaseUseCase = new GetPurchaseUseCase(
                        $purchaseRepository,
                        $purchaseItemRepository
                );

                $this->cancelPurchaseUseCase = new CancelPurchaseUseCase(
                        $purchaseRepository,
                        $purchaseItemRepository,
                        $productRepository
                );

                $this->generateInvoiceUseCase = new GenerateInvoiceUseCase(
                        $purchaseRepository,
                        $purchaseItemRepository,
                        $customerRepository,
                        $productRepository
                );
        }

        // * ============================================================
        // * LISTAR VENTAS...
        // * ============================================================
        public function index(): void
        {
                try {
                        $purchases = $this->listPurchasesUseCase->execute();
                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/purchases/index.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl());
                }
        }

        // * ============================================================
        // * DETALLE DE VENTA...
        // * ============================================================
        public function show(): void
        {
                $id = $_GET['id'];

                try {
                        $purchaseDTO = $this->getPurchaseUseCase->execute((int) $id);

                        if (!$purchaseDTO) {
                                throw new \Exception('Venta no encontrada');
                        }

                        $purchase = $purchaseDTO;
                        $viewsPath = PathHelper::getBasePath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/purchases/show.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/purchases');
                        exit;
                }
        }

        // * ============================================================
        // * NUEVA VENTA (Formulario)...
        // * ============================================================
        public function create(): void
        {
                try {
                        // > Obtener todos los productos activos para el selector...
                        $product = $this->productRepository->findActive();
                        $customers = $this->customerRepository->findAll();

                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/purchases/create.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/purchases');
                        exit;
                }
        }

        // * ============================================================
        // * GUARDAR VENTA...
        // * ============================================================
        public function store(): void
        {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        header('Location: ' . PathHelper::getBaseUrl() . '/purchases');
                        exit;
                }

                try {
                        // > Obtener los items del formulario...
                        $items = [];
                        $productIds = $_POST['product_id'] ?? [];
                        $quantities = $_POST['quantity'] ?? [];

                        for ($i = 0; $i < count($productIds); $i++) {
                                if (
                                        !empty($productIds[$i]) &&
                                        !empty($quantities[$i]) &&
                                        $quantities[$i] > 0
                                ) {
                                        $items[] = [
                                                'product_id' => (int) $productIds[$i],
                                                'quantity' => (int) $quantities[$i]
                                        ];
                                }
                        }

                        if (empty($items)) {
                                throw new \Exception('Debe agregar al menos un producto');
                        }

                        $purchaseDTO = new PurchaseDTO([
                                'customer_id' => (int) $_POST['customer_id'],
                                'user_id' => $_SESSION['user_id'] ?? null,
                                'payment_method' => $_POST['payment_method'] ?? 'cash',
                                'is_online' => false,
                                'notes' => $_POST['notes'] ?? null,
                                'items' => $items
                        ]);

                        $result = $this->createPurchaseUseCase->execute($purchaseDTO);

                        $_SESSION['success_message'] =
                                $result['message']
                                . ' - Factura: '
                                . $result['invoice_number'];

                        // > Redirigir a la vista de la factura...
                        header('Location: ' . PathHelper::getBaseUrl() . '/purchases/invoice?id=' . $result['purchase_id']);
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/purchases/create');
                }
                exit;
        }

        // * ============================================================
        // * ANULAR VENTA...
        // * ============================================================
        public function cancel(): void
        {
                $id = $_GET['id'] ?? 0;
                $reason = $_POST['reason'] ?? '';

                try {
                        $result = $this->cancelPurchaseUseCase->execute((int) $id, $reason);
                        $_SESSION['success_message'] = $result['message'];
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                }

                header('Location: ' . PathHelper::getBaseUrl() . '/purchases');
                exit;
        }

        // * ============================================================
        // * VER FACTURA...
        // * ============================================================
        public function invoice(): void
        {
                $id = $_GET['id'] ?? 0;

                try {
                        $invoiceData = $this->generateInvoiceUseCase->execute((int) $id);

                        $purchase = $invoiceData['purchase'];
                        $customer = $invoiceData['customer'];
                        $items = $invoiceData['items'];
                        $company = $invoiceData['company'];

                        $viewsPath = PathHelper::getViewsPath();

                        require_once $viewsPath . '/layouts/navbar.php';
                        require_once $viewsPath . '/purchases/invoice.php';
                } catch (\Exception $e) {
                        $_SESSION['error_message'] = $e->getMessage();
                        header('Location: ' . PathHelper::getBaseUrl() . '/purchases');
                        exit;
                }
        }
}
