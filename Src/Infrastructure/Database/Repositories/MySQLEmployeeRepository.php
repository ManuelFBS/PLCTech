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
                $stmt = $this->db->prepare('SELECT * FROM employees WHERE id = ?');
                $stmt->execute([$id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$data) {
                        return null;
                }

                return $this->mapToEntity($data);
        }

        public function findByDni(string $dni): ?Employee
        {
                $stmt = $this->db->prepare('SELECT * FROM employees WHERE dni = ?');
                $stmt->execute([$dni]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByEmail(string $email): ?Employee
        {
                $stmt = $this->db->prepare('SELECT * FROM employees WHERE email = ?');
                $stmt->execute([$email]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findAll(): array
        {
                $stmt = $this->db->query('SELECT * FROM employees ORDER BY id DESC');
                $employees = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $employees[] = $this->mapToEntity($data);
                }

                return $employees;
        }

        public function save(Employee $employee): int
        {
                $stmt = $this->db->prepare(
                        'INSERT INTO employees (dni, names, surnames, birthdate, email, address, phone_number)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                '
                );

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
                $stmt = $this->db->prepare(
                        'UPDATE employees 
                        SET dni = ?, names = ?, surnames = ?, birthdate = ?, email = ?, address = ?, phone_number = ?
                        WHERE id = ?'
                );

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
                $stmt = $this->db->prepare('DELETE FROM employees WHERE id = ?');
                $stmt->execute([$id]);
        }

        private function mapToEntity(array $data): Employee
        {
                // ? Depuración para verificar que los datos llegan correctamente..
                error_log('Mapeando empleado ID: ' . ($data['id'] ?? 'sin id'));

                return new Employee(
                        (int) $data['id'],  // id
                        (string) $data['dni'],  // dni
                        (string) $data['names'],  // names
                        (string) $data['surnames'],  // surnames
                        (string) $data['birthdate'],  // birthdate
                        (string) $data['email'],  // email
                        (string) $data['address'],  // address
                        $data['phone_number'] ?? null,  // phone_number
                        (string) $data['created_at'],  // created_at
                        $data['updated_at'] ?? null  // updated_at
                );
        }
}
