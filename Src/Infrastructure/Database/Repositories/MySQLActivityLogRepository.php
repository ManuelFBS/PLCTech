<?php

namespace PLCTech\Infrastructure\Database\Repositories;

use PLCTech\Config\DB\Database;
use PLCTech\Domain\Entities\ActivityLog;
use PLCTech\Domain\Repositories\ActivityLogRepositoryInterface;
use PDO;

class MySQLActivityLogRepository implements ActivityLogRepositoryInterface
{
        private PDO $db;

        public function __construct()
        {
                $this->db = Database::getConnection();
        }

        public function save(ActivityLog $log): int
        {
                $stmt = $this->db->prepare('
            INSERT INTO activity_logs (user_id, username, action, description, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?)
        ');

                $stmt->execute([
                        $log->getUserId(),
                        $log->getUsername(),
                        $log->getAction(),
                        $log->getDescription(),
                        $log->getIpAddress(),
                        $log->getUserAgent()
                ]);

                return (int) $this->db->lastInsertId();
        }

        public function findByUser(int $userId): array
        {
                $stmt = $this->db->prepare('
            SELECT * FROM activity_logs 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ');
                $stmt->execute([$userId]);
                $logs = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $logs[] = $this->mapToEntity($data);
                }

                return $logs;
        }

        public function findByAction(string $action): array
        {
                $stmt = $this->db->prepare('
            SELECT * FROM activity_logs 
            WHERE action = ? 
            ORDER BY created_at DESC
        ');
                $stmt->execute([$action]);
                $logs = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $logs[] = $this->mapToEntity($data);
                }

                return $logs;
        }

        public function findAll(int $limit = 100): array
        {
                $stmt = $this->db->prepare('
            SELECT * FROM activity_logs 
            ORDER BY created_at DESC 
            LIMIT ?
        ');
                $stmt->execute([$limit]);
                $logs = [];

                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $logs[] = $this->mapToEntity($data);
                }

                return $logs;
        }

        public function getRecent(int $limit = 50): array
        {
                return $this->findAll($limit);
        }

        private function mapToEntity(array $data): ActivityLog
        {
                return new ActivityLog(
                        (int) $data['id'],
                        $data['user_id'] ? (int) $data['user_id'] : null,
                        $data['username'] ?? null,
                        $data['action'],
                        $data['description'] ?? null,
                        $data['ip_address'] ?? null,
                        $data['user_agent'] ?? null,
                        $data['created_at']
                );
        }
}
