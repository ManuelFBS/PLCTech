<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\Customer;

interface CustomerRepositoryInterface
{
        public function find(int $id): ?Customer;
        public function findByDni(string $dni): ?Customer;
        public function findByEmail(string $email): ?Customer;
        public function findAll(): array;
        public function save(Customer $customer): int;
        public function update(Customer $customer): void;
        public function delete(int $id): void;
}
