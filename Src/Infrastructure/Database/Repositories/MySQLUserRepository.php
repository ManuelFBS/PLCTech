<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\User;
use PLCTech\Domain\Repositories\UserRepositoryInterface;
use PDO;

class MySQLUserRepository implements UserRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function find(int $id): ?User
        {
                $sql = 'SELECT * FROM users WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
                $data = $stmt->fetch();

                if (!$data)
                        return null;

                return $this->mapToEntity($data);
        }

        public function findByEmail(string $email): ?User
        {
                $sql = 'SELECT * FROM users WHERE email = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$email]);
                $data = $stmt->fetch();

                if (!$data)
                        return null;

                return $this->mapToEntity($data);
        }

        public function findByUsername(string $username): ?User
        {
                $sql = 'SELECT * FROM users WHERE user = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$username]);
                $data = $stmt->fetch();

                if (!$data)
                        return null;

                return $this->mapToEntity($data);
        }

        public function findByDni(string $dni): ?User
        {
                $sql = 'SELECT * FROM users WHERE dni = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$dni]);
                $data = $stmt->fetch();

                if (!$data)
                        return null;

                return $this->mapToEntity($data);
        }

        public function findAll(): array
        {
                $sql = 'SELECT * FROM users ORDER BY id DESC';
                $stmt = $this->db->query($sql);
                $users = [];

                while ($data = $stmt->fetch()) {
                        $users[] = $this->mapToEntity($data);
                }

                return $users;
        }

        public function findByEmployeeId(int $employeeId): ?User
        {
                $sql = 'SELECT * FROM users WHERE employee_id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$employeeId]);
                $data = $stmt->fetch();

                return $data ? $this->mapToEntity($data) : null;
        }

        public function findByCustomerId(int $customerId): ?User
        {
                $sql = 'SELECT * FROM users WHERE customer_id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$customerId]);
                $data = $stmt->fetch();

                return $data ? $this->mapToEntity($data) : null;
        }

        public function save(User $user): void
        {
                $sql =
                        'INSERT INTO users (dni, user, email, role, password, is_active, employee_id, customer_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                        $user->getDni(),
                        $user->getUser(),
                        $user->getEmail(),
                        $user->getRole(),
                        $user->getPassword(),
                        $user->isActive() ? 1 : 0,
                        $user->getEmployeeId(),
                        $user->getCustomerId()
                ]);

                // ! No se necesita asignar el ID al objeto original si no se usará después...
                // ! Si se necesita, mejor retornar el ID o crear un método específico...
        }

        public function update(User $user): void
        {
                $sql = 'UPDATE users 
                                SET dni = ?, user = ?, email = ?, role = ?, password = ?, 
                                        is_active = ?, employee_id = ?, customer_id = ?, last_login = ?
                                WHERE id = ?';
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                        $user->getDni(),
                        $user->getUser(),
                        $user->getEmail(),
                        $user->getRole(),
                        $user->getPassword(),
                        $user->isActive() ? 1 : 0,
                        $user->getEmployeeId(),
                        $user->getCustomerId(),
                        $user->getLastLogin(),
                        $user->getId()
                ]);
        }

        public function delete(int $id): void
        {
                $sql = 'DELETE FROM users WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
        }

        public function updateLastLogin(int $id, string $timestamp): void
        {
                $sql = 'UPDATE users SET last_login = ? WHERE id = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$timestamp, $id]);
        }

        private function mapToEntity(array $data): User
        {
                return new User(
                        (int) $data['id'],
                        $data['dni'],
                        $data['user'],
                        $data['email'],
                        $data['role'],
                        $data['password'],
                        (bool) $data['is_active'],
                        $data['employee_id'] ? (int) $data['employee_id'] : null,
                        $data['customer_id'] ? (int) $data['customer_id'] : null,
                        $data['last_login'],
                        $data['created_at'],
                        $data['updated_at']
                );
        }
}
