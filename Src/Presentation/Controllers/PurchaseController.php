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

class PurchaseController
{
        private CreatePurchaseUseCase $createPurchaseUseCase;
        private ListPurchasesUseCase $listPurchaseUseCase;
        private GetPurchaseUseCase $getPurchaseUseCase;
        private CancelPurchaseUseCase $cancelPurchaseUseCase;
        private GenerateInvoiceUseCase $generateInvoiceUseCase;

        public function __construct() {}
}
