<?php

namespace PLCTech\Application\DTOs;

class UserDTO
{
        public ?int $id;
        public string $dni;
        public string $user;
        public string $email;
        public string $role;
        public string $password;
        public bool $is_active;
        public ?int $employee_id;
        public ?int $customer_id;
        public ?string $last_login;
        public ?string $created_at;

        public function __construct(array $data = [])
        {
                $this->id = $data['id'] ?? null;
                $this->dni = $data['dni'] ?? '';
                $this->user = $data['user'] ?? '';
                $this->email = $data['email'] ?? '';
                $this->role = $data['role'] ?? '';
                $this->password = $data['password'] ?? '';
                $this->is_active = $data['is_active'] ?? true;
                $this->employee_id = $data['employee_id'] ?? null;
                $this->customer_id = $data['customer_id'] ?? null;
                $this->last_login = $data['last_login'] ?? null;
                $this->created_at = $data['created_at'] ?? null;
        }

        public function toArray(): array
        {
                return [
                        'id' => $this->id,
                        'dni' => $this->dni,
                        'user' => $this->user,
                        'email' => $this->email,
                        'role' => $this->role,
                        'is_active' => $this->is_active,
                        'employee_id' => $this->employee_id,
                        'customer_id' => $this->customer_id,
                        'last_login' => $this->last_login,
                        'created_at' => $this->created_at
                ];
        }
}
