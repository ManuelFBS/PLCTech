<?php

namespace PLCTech\Application\DTOs;

class UserDTO
{
        public int $id;
        public string $dni;
        public string $user;
        public string $email;
        public string $role;
        public ?string $password;  // > ← Agregar esta propiedad (nullable)...
        public bool $is_active;
        public ?int $employee_id;
        public ?int $customer_id;
        public ?string $last_login;
        public string $created_at;

        public function __construct(array $data)
        {
                $this->id = (int) ($data['id'] ?? 0);
                $this->dni = (string) ($data['dni'] ?? '');
                $this->user = (string) ($data['user'] ?? '');
                $this->email = (string) ($data['email'] ?? '');
                $this->role = (string) ($data['role'] ?? '');
                $this->password = $data['password'] ?? null;  // > ← Inicializar password...
                $this->is_active = (bool) ($data['is_active'] ?? true);
                $this->employee_id = $data['employee_id'] ? (int) $data['employee_id'] : null;
                $this->customer_id = $data['customer_id'] ? (int) $data['customer_id'] : null;
                $this->last_login = $data['last_login'] ?? null;
                $this->created_at = (string) ($data['created_at'] ?? '');
        }
}
