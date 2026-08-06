<?php

namespace PLCTech\Domain\Repositories;

use PLCTech\Domain\Entities\ActivityLog;

interface ActivityLogRepositoryInterface
{
        public function save(ActivityLog $log): int;
        public function findByUser(int $userId): array;
        public function findByAction(string $action): array;
        public function findAll(int $limit = 100): array;
        public function getRecent(int $limit = 50): array;
}
