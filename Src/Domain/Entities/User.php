<?php

namespace PLCTech\Domain\Entities;

use PLCTech\Domain\ValueObjects\DNI;
use PLCTech\Domain\ValueObjects\Email;

class User
{
        private ?int $id;
        private DNI $dni;
        private string $user;
        private Email $email;
        private string $role;
        private string $password;
        private bool $isActive;
        private ?int $employeeId;
        private ?int $customerId;
        private ?\DateTime $createdAt;
        private ?\DateTime $updatedAt;
        private ?\DateTime $lastLogin;

        public function __construct(
                DNI $dni,
                string $user,
                Email $email,
                string $role,
                string $password,
                ?int $id = null,
                ?int $employeeId = null,
                ?int $customerId = null,
                bool $isActive = true,
                ?\DateTime $createdAt = null,
                ?\DateTime $updatedAt = null,
                ?\DateTime $lastLogin = null
        ) {
                $this->dni = $dni;
                $this->user = $user;
                $this->email = $email;
                $this->role = $role;
                $this->password = $password;
                $this->id = $id;
                $this->employeeId = $employeeId;
                $this->customerId = $customerId;
                $this->isActive = $isActive;
                $this->createdAt = $createdAt ?? new \DateTime();
                $this->updatedAt = $updatedAt ?? new \DateTime();
                $this->lastLogin = $lastLogin;
        }

        // * Getters...
        public function getId(): ?int
        {
                return $this->id;
        }

        public function getDni(): DNI
        {
                return $this->dni;
        }

        public function getUser(): string
        {
                return $this->user;
        }

        public function getEmail(): Email
        {
                return $this->email;
        }

        public function getRole(): string
        {
                return $this->role;
        }

        public function getPassword(): string
        {
                return $this->password;
        }

        public function isActive(): bool
        {
                return $this->isActive;
        }

        public function getEmployeeId(): ?int
        {
                return $this->employeeId;
        }

        public function getCustomerId(): ?int
        {
                return $this->customerId;
        }

        // * Métodos de negocio...
        public function hasRole(string $role): bool
        {
                return $this->role === $role;
        }

        public function canAccessAdminPanel(): bool
        {
                return $this->role === 'Admin';
        }

        public function isEmployee(): bool
        {
                return $this->role === 'Employee';
        }

        public function isCustomer(): bool
        {
                return $this->role === 'Customer';
        }

        public function block(): void
        {
                $this->isActive = false;
        }

        public function unblock(): void
        {
                $this->isActive = true;
        }

        public function updateLastLogin(): void
        {
                $this->lastLogin = new \DateTime();
        }
}

?>