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

        public function __construct(array $data = [])
        {
                $this->id = $data['id'] ?? null;
                $this->dni = $data['dni'] ?? '';
                $this->full_name = $data['full_name'] ?? '';
                $this->birthdate = $data['birthdate'] ?? '';
                $this->email = $data['email'] ?? '';
                $this->phone_number = $data['phone_number'] ?? null;
                $this->created_at = $data['created_at'] ?? null;
        }
}
