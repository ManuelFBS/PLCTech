<?php

namespace PLCTech\Domain\Entities;

class User
{
        private ?int $id;
        private string $dni;
        private string $user;
        private string $email;
        private string $role;
        private string $password;
        private bool $is_active;
        private ?int $employee_id;
        private ?int $customer_id;
        private ?string $last_login;
        private string $created_at;
        private ?string $updated_at;

        public function __construct(
                ?int $id,
                string $dni,
                string $user,
                string $email,
                string $role,
                string $password,
                bool $is_active = true,
                ?int $employee_id = null,
                ?int $customer_id = null,
                ?string $last_login = null,
                string $created_at = '',
                ?string $updated_at = null
        ) {
                $this->id = $id;
                $this->dni = $dni;
                $this->user = $user;
                $this->email = $email;
                $this->role = $role;
                $this->password = $password;
                $this->is_active = $is_active;
                $this->employee_id = $employee_id;
                $this->customer_id = $customer_id;
                $this->last_login = $last_login;
                $this->created_at = $created_at;
                $this->updated_at = $updated_at;
        }

        // * Getters...
        public function getId(): ?int
        {
                return $this->id;
        }

        public function getDni(): string
        {
                return $this->dni;
        }

        public function getUser(): string
        {
                return $this->user;
        }

        public function getEmail(): string
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
                return $this->is_active;
        }

        public function getEmployeeId(): ?int
        {
                return $this->employee_id;
        }

        public function getCustomerId(): ?int
        {
                return $this->customer_id;
        }

        public function getLastLogin(): ?string
        {
                return $this->last_login;
        }

        public function getCreatedAt(): string
        {
                return $this->created_at;
        }

        public function getUpdatedAt(): ?string
        {
                return $this->updated_at;
        }

        // * Setters...
        public function setLastLogin(?string $last_login): void
        {
                $this->last_login = $last_login;
        }

        public function setIsActive(bool $is_active): void
        {
                $this->is_active = $is_active;
        }

        // * Métodos útiles...
        public function isAdmin(): bool
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

        public function verifyPassword(string $plain_password): bool
        {
                return password_verify($plain_password, $this->password);
        }
}
