<?php

namespace PLCTech\Application\DTOs;

class CustomerDTO
{
        public ?int $id;
        public string $dni;
        public string $full_name;
        public string $birthdate;
        public string $email;
        public ?string $phone_number;
        public ?string $created_at;

        public function __construct(array $data)
        {
                $this->id = (int) ($data['id'] ?? 0);
                $this->dni = (string) ($data['dni'] ?? '');
                $this->full_name = (string) ($data['full_name'] ?? '');
                $this->birthdate = (string) ($data['birthdate'] ?? '');
                $this->email = (string) ($data['email'] ?? '');
                $this->phone_number = $data['phone_number'] ?? null;
                $this->created_at = (string) ($data['created_at'] ?? '');
        }

        public function getFullName(): string
        {
                return $this->full_name;
        }

        // * Método para depuración...
        public function toArray(): array
        {
                return [
                        'id' => $this->id,
                        'dni' => $this->dni,
                        'full_name' => $this->full_name,
                        'birthdate' => $this->birthdate,
                        'email' => $this->email,
                        'phone_number' => $this->phone_number,
                        'created_at' => $this->created_at
                ];
        }
}
