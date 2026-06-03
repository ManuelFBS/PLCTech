<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\User;

interface UserRepositoryInterface
{
        public function find(int $id): ?User;
        public function findByEmail(string $email): ?User;
        public function findByUsername(string $username): ?User;
        public function findByDni(string $dni): ?User;
        public function findAll(): array;
        public function save(User $user): void;
        public function update(User $user): void;
        public function delete(int $id): void;
        public function updateLastLogin(int $id, string $timestamp): void;
}
