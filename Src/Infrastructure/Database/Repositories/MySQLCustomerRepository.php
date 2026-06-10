<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\Customer;
use PLCTech\Domain\Repositories\CustomerRepositoryInterface;
use PDO;

class MySQLCustomerRepository implements CustomerRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function find(int $id): ?Customer
        {
                $sql = 'SELECT * FROM customers WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
                $data = $stmt->fetch();

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByDni(string $dni): ?Customer
        {
                $sql = 'SELECT * FROM customers WHERE dni = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$dni]);
                $data = $stmt->fetch();

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByEmail(string $email): ?Customer
        {
                $sql = 'SELECT * FROM customers WHERE email = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$email]);
                $data = $stmt->fetch();

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findAll(): array
        {
                $sql = 'SELECT * FROM customers ORDER BY full_name ASC';
                $stmt = $this->db->query($sql);
                $customers = [];

                while ($data = $stmt->fetch()) {
                        $customers[] = $this->mapToEntity($data);
                }

                return $customers;
        }

        public function save(Customer $customer): int
        {
                $sql = 'INSERT INTO customers (dni, full_name, birthdate, email, phone_number)
                                VALUES (?, ?, ?, ?, ?)';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                        $customer->getDni(),
                        $customer->getFullName(),
                        $customer->getBirthdate(),
                        $customer->getEmail(),
                        $customer->getPhoneNumber()
                ]);

                return (int) $this->db->lastInsertId();
        }

        public function update(Customer $customer): void
        {
                $sql = 'UPDATE customers 
                                SET dni = ?, full_name = ?, birthdate = ?, email = ?, phone_number = ?
                                WHERE id = ?';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                        $customer->getDni(),
                        $customer->getFullName(),
                        $customer->getBirthdate(),
                        $customer->getEmail(),
                        $customer->getPhoneNumber(),
                        $customer->getId()
                ]);
        }

        public function delete(int $id): void
        {
                $sql = 'DELETE FROM customers WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
        }

        private function mapToEntity(array $data): Customer
        {
                return new Customer(
                        (int) $data['id'],
                        $data['dni'],
                        $data['full_name'],
                        $data['birthdate'],
                        $data['email'],
                        $data['phone_number'],
                        $data['created_at'],
                        $data['updated_at']
                );
        }
}
