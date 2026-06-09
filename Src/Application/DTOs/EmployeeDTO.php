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

        public function __construct(array $data)
        {
                $this->id = (int) ($data['id'] ?? 0);
                $this->dni = (string) ($data['dni'] ?? '');
                $this->names = (string) ($data['names'] ?? '');
                $this->surnames = (string) ($data['surnames'] ?? '');
                $this->birthdate = (string) ($data['birthdate'] ?? '');
                $this->email = (string) ($data['email'] ?? '');
                $this->address = (string) ($data['address'] ?? '');
                $this->phone_number = $data['phone_number'] ?? null;
                $this->created_at = (string) ($data['created_at'] ?? '');
        }

        public function getFullName(): string
        {
                return $this->names . ' ' . $this->surnames;
        }

        // * Método para depuración...
        public function toArray(): array
        {
                return [
                        'id' => $this->id,
                        'dni' => $this->dni,
                        'names' => $this->names,
                        'surnames' => $this->surnames,
                        'birthdate' => $this->birthdate,
                        'email' => $this->email,
                        'address' => $this->address,
                        'phone_number' => $this->phone_number,
                        'created_at' => $this->created_at
                ];
        }
}
