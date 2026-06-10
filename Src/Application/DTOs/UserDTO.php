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

        public function __construct(array $data)
        {
                $this->id = (int) $data['id'] ?? null;
                $this->dni = (string) $data['dni'] ?? '';
                $this->user = (string) $data['user'] ?? '';
                $this->email = (string) $data['email'] ?? '';
                $this->role = (string) $data['role'] ?? '';
                $this->password = (string) $data['password'] ?? '';
                $this->is_active = (bool) $data['is_active'] ?? true;
                $this->employee_id = (int) $data['employee_id'] ?? null;
                $this->customer_id = (int) $data['customer_id'] ?? null;
                $this->last_login = (string) $data['last_login'] ?? null;
                $this->created_at = (string) $data['created_at'] ?? '';
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
