<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\Employee;

interface EmployeeRepositoryInterface
{
        public function find(int $id): ?Employee;
        public function findByDni(string $dni): ?Employee;
        public function findByEmail(string $email): ?Employee;
        public function findAll(): array;
        public function save(Employee $employee): int;
        public function update(Employee $employee): void;
        public function delete(int $id): void;
}
