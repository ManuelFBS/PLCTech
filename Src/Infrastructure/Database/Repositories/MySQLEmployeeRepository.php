<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\Employee;
use PLCTech\Domain\Repositories\EmployeeRepositoryInterface;
use PDO;

class MySQLEmployeeRepository implements EmployeeRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function find(int $id): ?Employee
        {
                $sql = 'SELECT * FROM employees WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
                $data = $stmt->fetch();

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByDni(string $dni): ?Employee
        {
                $sql = 'SELECT * FROM employees WHERE dni = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$dni]);
                $data = $stmt->fetch();

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByEmail(string $email): ?Employee
        {
                $sql = 'SELECT * FROM employees WHERE email = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$email]);
                $data = $stmt->fetch();

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findAll(): array
        {
                $sql = 'SELECT * FROM employees ORDER BY id DESC';
                $stmt = $this->db->query($sql);
                $employees = [];

                while ($data = $stmt->fetch()) {
                        $employees[] = $this->mapToEntity($data);
                }

                return $employees;
        }

        public function save(Employee $employee): int
        {
                $sql = 'INSERT INTO employees (dni, names, surnames, birthdate, email, address, phone_number)
                                VALUES (?, ?, ?, ?, ?, ?, ?)';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                        $employee->getDni(),
                        $employee->getNames(),
                        $employee->getSurnames(),
                        $employee->getBirthdate(),
                        $employee->getEmail(),
                        $employee->getAddress(),
                        $employee->getPhoneNumber()
                ]);

                return (int) $this->db->lastInsertId();
        }

        public function update(Employee $employee): void
        {
                $sql = 'UPDATE employees 
                                SET dni = ?, names = ?, surnames = ?, birthdate = ?, email = ?, address = ?, phone_number = ?
                                WHERE id = ?';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                        $employee->getDni(),
                        $employee->getNames(),
                        $employee->getSurnames(),
                        $employee->getBirthdate(),
                        $employee->getEmail(),
                        $employee->getAddress(),
                        $employee->getPhoneNumber(),
                        $employee->getId()
                ]);
        }

        public function delete(int $id): void
        {
                $sql = 'DELETE FROM employees WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
        }

        private function mapToEntity(array $data): Employee
        {
                return new Employee(
                        (int) $data['id'],
                        $data['dni'],
                        $data['names'],
                        $data['surnames'],
                        $data['birthdate'],
                        $data['email'],
                        $data['address'],
                        $data['phone_number'],
                        $data['created_at'],
                        $data['updated_at']
                );
        }
}
