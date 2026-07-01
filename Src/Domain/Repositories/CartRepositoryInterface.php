<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\Cart;

interface CartRepositoryInterface
{
        // * Encuentra un item del carrito por su ID...
        public function find(int $id): ?Cart;

        // * Encuentra todos los items del carrito de un cliente...
        public function findByCustomer(int $customerId): array;

        // * Encuentra un item del carrito por cliente y producto...
        public function findByCustomerAndProduct(int $customerId, int $productId): ?Cart;

        // * Guarda un nuevo item en el carrito...
        public function save(Cart $cart): int;

        // * Actualiza un item del carrito...
        public function update(Cart $cart): void;

        // * Elimina un item del carrito...
        public function delete(int $id): void;

        // * Elimina todos los items del carrito de un cliente...
        public function clearByCustomer(int $customerId): void;

        // * Elimina un item específico del carrito de un cliente...
        public function deleteByCustomerAndProduct(int $customerId, int $productId): void;
}
