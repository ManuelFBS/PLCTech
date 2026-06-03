<?php

namespace PLCTech\Domain\Entities;

class Customer
{
        private ?int $id;
        private string $dni;
        private string $full_name;
        private string $birthdate;
        private string $email;
        private ?string $phone_number;
        private string $created_at;
        private ?string $updated_at;

        public function __construct(
                ?int $id,
                string $dni,
                string $full_name,
                string $birthdate,
                string $email,
                ?string $phone_number = null,
                string $created_at = '',
                ?string $updated_at = null
        ) {
                $this->id = $id;
                $this->dni = $dni;
                $this->full_name = $full_name;
                $this->birthdate = $birthdate;
                $this->email = $email;
                $this->phone_number = $phone_number;
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

        public function getFullName(): string
        {
                return $this->full_name;
        }

        public function getBirthdate(): string
        {
                return $this->birthdate;
        }

        public function getEmail(): string
        {
                return $this->email;
        }

        public function getPhoneNumber(): ?string
        {
                return $this->phone_number;
        }

        public function getCreatedAt(): string
        {
                return $this->created_at;
        }

        public function getUpdatedAt(): ?string
        {
                return $this->updated_at;
        }
}
