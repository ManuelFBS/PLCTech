<?php

namespace PLCTech\Domain\Entities;

class Employee
{
        private ?int $id;
        private string $dni;
        private string $names;
        private string $surnames;
        private string $birthdate;
        private string $email;
        private string $address;
        private ?string $phone_number;
        private string $created_at;
        private ?string $updated_at;

        public function __construct(
                ?int $id,
                string $dni,
                string $names,
                string $surnames,
                string $birthdate,
                string $email,
                string $address,
                ?string $phone_number = null,
                string $created_at = '',
                ?string $updated_at = null
        ) {
                $this->id = $id;
                $this->dni = $dni;
                $this->names = $names;
                $this->surnames = $surnames;
                $this->birthdate = $birthdate;
                $this->email = $email;
                $this->address = $address;
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

        public function getNames(): string
        {
                return $this->names;
        }

        public function getSurnames(): string
        {
                return $this->surnames;
        }

        public function getFullName(): string
        {
                return $this->names . ' ' . $this->surnames;
        }

        public function getBirthdate(): string
        {
                return $this->birthdate;
        }

        public function getEmail(): string
        {
                return $this->email;
        }

        public function getAddress(): string
        {
                return $this->address;
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
