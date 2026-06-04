<?php

namespace PLCTech\Application\DTOs;

class EmployeeDTO
{
        public ?int $id;
        public string $dni;
        public string $names;
        public string $surnames;
        public string $birthdate;
        public string $email;
        public string $address;
        public ?string $phone_number;
        public ?string $created_at;

        public function __construct(array $data = [])
        {
                $this->id = $data['id'] ?? null;
                $this->dni = $data['dni'] ?? '';
                $this->dni = $data['names'] ?? '';
                $this->dni = $data['surnames'] ?? '';
                $this->dni = $data['birthdate'] ?? '';
                $this->dni = $data['email'] ?? '';
                $this->dni = $data['address'] ?? '';
                $this->dni = $data['phone_number'] ?? null;
                $this->dni = $data['created_at'] ?? null;
        }

        public function getFullName(): string
        {
                return $this->names . ' ' . $this->surnames;
        }
}
